<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Subscription;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Plan;
use App\Models\Coupon;
use Stripe\PaymentIntent;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Http\Controllers\Controller;


    use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Stripe\Checkout\Session as CheckoutSession;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    // Fetch the list of available plans from the database
    public function plans()
    {
        $plans = Plan::all(); // Fetch all plans from the database

        return response()->json([
            'plans' => $plans
        ]);
    }



     // Handle the subscription request
public function subscribe(Request $request)
{
    $validator = Validator::make($request->all(), [
        'plan_id' => 'required|exists:plans,id',
        'success_url' => 'required',
        'cancel_url' => 'required',
        'coupon_code' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = Auth::user();
    $plan = Plan::findOrFail($request->plan_id);
    $transaction_id = Str::uuid();

    // Calculate end date
    if (is_numeric($plan->duration)) {
        $endDate = now()->addMonths((int)$plan->duration);
    } elseif (preg_match('/^(\d+)\s*(month|months)$/i', $plan->duration, $matches)) {
        $endDate = now()->addMonths((int)$matches[1]);
    } elseif (preg_match('/^(\d+)\s*(year|years)$/i', $plan->duration, $matches)) {
        $endDate = now()->addYears((int)$matches[1]);
    } elseif (strtolower($plan->duration) === 'lifetime') {
        $endDate = null;
    } else {
        $endDate = now()->addMonth();
    }

    $originalAmount = $plan->discounted_price;
    $discountAmount = 0;
    $discountPercent = 0;
    $couponCode = null;
    $finalAmount = $originalAmount;

    // Coupon logic
    if ($request->filled('coupon_code')) {
        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
            ->where(function ($query) {
            $now = now();
            $query->where(function ($q) use ($now) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
            })->where(function ($q) use ($now) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', $now);
            });
            })
            ->first();

        if (
            !$coupon ||
            (!is_null($coupon->valid_from) && now()->lt($coupon->valid_from)) ||
            (!is_null($coupon->valid_until) && now()->gt($coupon->valid_until))
        ) {
            return response()->json(['errors' => ['coupon_code' => ['Invalid or expired coupon code.']]], 400);
        }

        $couponCode = $coupon->code;
        Log::info('Coupon applied', [
            'coupon_code' => $couponCode,
            'coupon_type' => $coupon->type,
            'value' => $coupon->value,
        ]);

   
        if ($coupon->type == 'percentage') {
            $discountPercent = $coupon->value;
            $discountAmount = ($originalAmount * $discountPercent) / 100;
        } elseif ($coupon->type == 'fixed') {
            $discountAmount = $coupon->value;
            $discountPercent = ($discountAmount / $originalAmount) * 100;
        }

        $finalAmount = max($originalAmount - $discountAmount, 0);
    }

    // Create subscription
    $subscription = Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'start_date' => now(),
        'end_date' => $endDate,
        'original_amount' => $originalAmount,
        'final_amount' => $finalAmount,
        'amount' => $finalAmount,
        'payment_method' => 'Stripe Checkout',
        'transaction_id' => $transaction_id,
        'status' => 'Pending',
        'plan_features' => $plan->features,
        'coupon_code' => $couponCode,
        'discount_amount' => $discountAmount,
        'discount_percent' => $discountPercent,
    ]);

    // Stripe Checkout
    $checkoutSession = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $plan->name,
                ],
                'unit_amount' => $finalAmount * 100,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => $request->success_url . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $request->cancel_url . '?session_id={CHECKOUT_SESSION_ID}',
        'metadata' => [
            'subscription_id' => $subscription->id,
        ],
    ]);

    return response()->json([
        'url' => $checkoutSession->url
    ]);
}




public function webhook(Request $request)
{



    $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

    $payload = $request->getContent();
    $sigHeader = $request->server('HTTP_STRIPE_SIGNATURE');

    try {
        $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
    } catch (\UnexpectedValueException $e) {
        // Invalid payload
        return response('Invalid payload', 400);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
        return response('Invalid signature', 400);
    }


   Log::info('Stripe Webhook Event Received', [
        'type' => $event->type,
        'data' => $event->data->object,
    ]);
    // Handle the event
    switch ($event->type) {
        case 'checkout.session.completed':
            $session = $event->data->object;

            // Get the subscription ID from metadata
            $subscriptionId = $session->metadata->subscription_id ?? null;

            if ($subscriptionId) {
                $subscription = Subscription::find($subscriptionId);

                if ($subscription && $subscription->status !== 'Success') {
                    $subscription->status = 'Success';
                    $subscription->save();
                }
            }

            break;

        // Add more Stripe event cases if needed (e.g., payment_failed, subscription_canceled)
    }

    return response()->json(['status' => 'success'], Response::HTTP_OK);
}







    // Confirm Stripe Payment and update subscription status
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'payment_intent_id' => 'required|string',
        ]);

        $subscription = Subscription::findOrFail($request->subscription_id);

        // Retrieve the payment intent status from Stripe
        $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($paymentIntent->status === 'succeeded') {
            // Update subscription status to "Active" after successful payment
            $subscription->status = 'Active';
            $subscription->save();

            return response()->json([
                'message' => 'Payment successful, subscription activated',
                'subscription' => $subscription
            ]);
        }

        return response()->json([
            'message' => 'Payment failed',
        ], 400);
    }

    // Fetch the user's active subscription
    public function mySubscription()
    {
        $user = Auth::user();

        // Fetch the active subscription with its related plan
        $subscription = $user->subscriptions()->with('plan')->latest()->first();

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription found.'], 404);
        }

        return response()->json($subscription);
    }
}
