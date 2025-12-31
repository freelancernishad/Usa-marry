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
use App\Helpers\NotificationHelper;


    use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        $plans = Plan::orderBy('index_no', 'asc')->get(); // Get all plans ordered by index_no

        return response()->json([
            'plans' => $plans
        ]);
    }

private function createStripeCheckoutSession($plan, $finalAmount, $subscription, $successUrl, $cancelUrl)
{


    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $plan->name,
                ],
                'unit_amount' => (int) ($finalAmount * 100),
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $cancelUrl . '?session_id={CHECKOUT_SESSION_ID}',
        'metadata' => [
            'subscription_id' => $subscription->id,
        ],
    ]);

    return $session->url;
}


private function createCheckoutPaymentLink($user, $plan, $finalAmount, $subscription, $successUrl)
{
    $profile = $user->profile;

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . config('CHECKOUT_SECRET'),
        'Content-Type'  => 'application/json',
    ])->post('https://api.sandbox.checkout.com/payment-links', [

        'amount' => (int) ($finalAmount * 100),
        'currency' => 'USD',
        'reference' => 'SUB-' . $subscription->id,
        'description' => 'Subscription payment for ' . $plan->name,
        'display_name' => config('app.name'),
        'expires_in' => 604800,
        'processing_channel_id' => config('CHECKOUT_PROCESSING_CHANNEL_ID'),

        'customer' => [
            'email' => $user->email,
            'name'  => $user->name,
        ],

        'products' => [
            [
                'reference' => 'PLAN-' . $plan->id,
                'name'      => $plan->name,
                'quantity'  => 1,
                'price'     => (int) ($finalAmount * 100),
            ]
        ],

        'allow_payment_methods' => [
            'card',
            'applepay',
            'googlepay'
        ],

        // ✅ AUTO BILLING FROM MODELS
        'billing' => [
            'address' => [
                'address_line1' => $profile->institution
                    ?? $profile->occupation
                    ?? 'User Address',

                'address_line2' => $profile->family_location ?? null,
                'city'          => $profile->city ?? 'Unknown',
                'state'         => $profile->state ?? 'NA',
                'zip'           => '00000',
                'country'       => 'US',
            ],
            'phone' => [
                'country_code' => '+1',
                'number' => preg_replace('/\D/', '', $user->phone ?? '0000000000'),
            ],
        ],

        'return_url' => $successUrl . '?subscription_id=' . $subscription->id,
    ]);

    if (!$response->successful()) {
        Log::error('Checkout.com Error', [
            'status'   => $response->status(),
            'response' => $response->json(),
        ]);

        throw new \Exception($response->body());
    }

    return $response->json('_links.redirect.href');
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
    if ($request->coupon_code) {
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

            Log::info("Processing coupon code: " . $request->coupon_code);
            Log::info("Processing coupon result: " . json_encode($coupon));
        if (
            !$coupon ||
            (!is_null($coupon->valid_from) && now()->lt($coupon->valid_from)) ||
            (!is_null($coupon->valid_until) && now()->gt($coupon->valid_until))
        ) {
            return response()->json(['errors' => ['coupon_code' => ['Invalid or expired coupon code.']]], 400);
        }

        $couponCode = $coupon->code;



        if ($coupon->type == 'percentage') {
            $discountPercent = $coupon->value;
            $discountAmount = ($originalAmount * $discountPercent) / 100;
        } elseif ($coupon->type == 'fixed') {
            $discountAmount = $coupon->value;
            $discountPercent = ($discountAmount / $originalAmount) * 100;
        } elseif ($coupon->type == 'flat') {
            $discountAmount = $coupon->value;
            $discountPercent = ($discountAmount / $originalAmount) * 100;
        }

        $finalAmount = max($originalAmount - $discountAmount, 0);

        Log::info("Final amount after coupon application: " . $finalAmount);

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



$url = $this->createStripeCheckoutSession(
    $plan,
    $finalAmount,
    $subscription,
    $request->success_url,
    $request->cancel_url
) ?? '';

// Checkout.com Payment Link (CURRENT GATEWAY)
$CheckoutUrl = $this->createCheckoutPaymentLink(
    $user,
    $plan,
    $finalAmount,
    $subscription,
    $request->success_url
)?? '';

    // Stripe Checkout
    // $checkoutSession = \Stripe\Checkout\Session::create([
    //     'payment_method_types' => ['card'],
    //     'line_items' => [[
    //         'price_data' => [
    //             'currency' => 'usd',
    //             'product_data' => [
    //                 'name' => $plan->name,
    //             ],
    //             'unit_amount' => $finalAmount * 100,
    //         ],
    //         'quantity' => 1,
    //     ]],
    //     'mode' => 'payment',
    //     'success_url' => $request->success_url . '?session_id={CHECKOUT_SESSION_ID}',
    //     'cancel_url' => $request->cancel_url . '?session_id={CHECKOUT_SESSION_ID}',
    //     'metadata' => [
    //         'subscription_id' => $subscription->id,
    //     ],
    // ]);

    return response()->json([
        'url' => $url,
        'checkout_url' => $CheckoutUrl,

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

                                    // Load user and plan info for notification
                    $user = $subscription->user; // assuming Subscription model has user relationship
                    $planName = $subscription->plan->name ?? 'Your Plan'; // assuming subscription->plan relationship exists
                    $amount = $subscription->amount ?? 0; // or get from subscription or session object

                    if ($user) {
                        NotificationHelper::sendPlanPurchaseNotification($user, $planName, $amount, 'subscriptions', $subscription->id);
                    }


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

        // Active Subscription
        $subscription = $user->activeSubscription;

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription found.'], 404);
        }

        // Load the plan relationship
        $subscription->load('plan');

        // Contacts Viewed Count
        $contactsViewedCount = \App\Models\ContactView::where('user_id', $user->id)->count();

        // Default limit
        $totalViewContactLimit = 0;

        // Get view_contact feature from plan_features
        if ($subscription && is_array($subscription->plan_features)) {
            $feature = collect($subscription->plan_features)->firstWhere('key', 'view_contact');
            $totalViewContactLimit = isset($feature['value']) ? (int) $feature['value'] : 0;
        }

        // Calculate remaining balance and usage percentage
        $remainingBalance = max(0, $totalViewContactLimit - $contactsViewedCount);
        $contactViewUsagePercentage = $totalViewContactLimit > 0
            ? round(($contactsViewedCount / $totalViewContactLimit) * 100, 2)
            : 0;

        return response()->json([
            'subscription' => $subscription,
            'contacts_viewed' => $contactsViewedCount,
            'contact_view_limit' => $totalViewContactLimit,
            'contact_view_balance' => $remainingBalance,
            'usage_percentage' => $contactViewUsagePercentage,
        ]);
    }

    // Fetch all subscriptions of the authenticated user (latest first)
    public function subscriptionHistory()
    {
        $user = Auth::user();

        $subscriptions = $user->subscriptions()
            ->with('plan') // include related plan info
            ->orderBy('created_at', 'desc')
            ->get();

        if ($subscriptions->isEmpty()) {
            return response()->json(['message' => 'No Transaction history found.'], 404);
        }

        return response()->json($subscriptions);
    }



}
