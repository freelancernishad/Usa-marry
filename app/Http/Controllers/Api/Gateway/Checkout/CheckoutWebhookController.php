<?php

namespace App\Http\Controllers\Api\Gateway\Checkout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Subscription;
use App\Helpers\NotificationHelper;

class CheckoutWebhookController extends Controller
{
    public function webhook(Request $request)
    {
        Log::info('Checkout.com Webhook Invoked');
        $payload   = $request->getContent();
        $signature = $request->header('Cko-Signature');
        $secret    = config('CHECKOUT_WEBHOOK_SECRET');

        // 🔐 Verify signature (HMAC SHA256)
        if (!$this->verifyCheckoutSignature($payload, $signature, $secret)) {
            Log::warning('Checkout.com webhook invalid signature', [
                'signature' => $signature,
            ]);
            return response('Invalid signature', Response::HTTP_BAD_REQUEST);
        }

        $event = json_decode($payload, true);

        if (!$event || !isset($event['type'])) {
            return response('Invalid payload', Response::HTTP_BAD_REQUEST);
        }

        Log::info('Checkout.com Webhook Received', [
            'event_type' => $event['type'],
            'event_id'   => $event['id'] ?? null,
        ]);

        switch ($event['type']) {

            // ✅ FINAL SUCCESS
            case 'payment_captured':
                $this->handlePaymentCaptured($event);
                break;

            // ❌ FAILED / CANCELED
            case 'payment_declined':
            case 'payment_canceled':
                $this->handlePaymentFailed($event);
                break;

            default:
                Log::info('Unhandled Checkout.com event', [
                    'type' => $event['type'],
                ]);
                break;
        }

        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    /**
     * SUCCESS handler
     */
    private function handlePaymentCaptured(array $event): void
    {
        $reference = $event['data']['reference'] ?? null;

        if (!$reference) {
            Log::error('Checkout payment_captured missing reference');
            return;
        }

        // reference: SUB-{subscription_id}
        $subscriptionId = str_replace('SUB-', '', $reference);
        $subscription = Subscription::find($subscriptionId);

        if (!$subscription) {
            Log::error('Subscription not found', ['reference' => $reference]);
            return;
        }

        // Idempotency
        if ($subscription->status === 'Success') {
            return;
        }

        $subscription->update([
            'status'         => 'Success',
            'payment_method' => 'Checkout.com',
            'transaction_id' => $event['data']['id'] ?? null,
        ]);

        // 🔔 Notification
        $user = $subscription->user;
        $planName = $subscription->plan->name ?? 'Your Plan';
        $amount = $subscription->amount ?? 0;

        if ($user) {
            NotificationHelper::sendPlanPurchaseNotification(
                $user,
                $planName,
                $amount,
                'subscriptions',
                $subscription->id
            );
        }

        Log::info('Subscription activated via Checkout.com', [
            'subscription_id' => $subscription->id,
        ]);
    }

    /**
     * FAILED handler
     */
    private function handlePaymentFailed(array $event): void
    {
        $reference = $event['data']['reference'] ?? null;

        if (!$reference) {
            Log::error('Checkout failed event missing reference');
            return;
        }

        $subscriptionId = str_replace('SUB-', '', $reference);
        $subscription = Subscription::find($subscriptionId);

        if ($subscription) {
            $subscription->update([
                'status' => 'Failed',
            ]);
        }

        Log::warning('Checkout.com payment failed', [
            'event_type' => $event['type'],
            'reference'  => $reference,
        ]);
    }

    /**
     * 🔐 Signature verification helper
     */
    private function verifyCheckoutSignature(string $payload, ?string $signature, string $secret): bool
    {
        if (!$payload || !$signature || !$secret) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }
}
