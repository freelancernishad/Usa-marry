<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Subscription;

use Stripe\Stripe;
use App\Models\Plan;
use Stripe\PaymentIntent;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        // Fetch the plan from the database
        $plan = Plan::findOrFail($request->plan_id);

        // Generate a unique transaction_id (UUID or any custom logic)
        $transaction_id = Str::uuid(); // You can use uniqid() or a custom method here

        // Calculate the subscription's end date based on the plan's duration
        $endDate = $plan->duration === 'Lifetime' ? null : now()->addMonths($plan->duration);

        // Create a new subscription (but don't confirm payment yet)
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => $endDate,
            'amount' => $plan->discounted_price,
            'payment_method' => 'Stripe Checkout',
            'transaction_id' => $transaction_id,  // Store the generated transaction_id
            'status' => 'Pending', // Set status to "Pending" until payment is confirmed
        ]);


        $success_url = $request->success_url;
        $cancel_url = $request->cancel_url;

        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
            [
                'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $plan->name,
                ],
                'unit_amount' => $plan->discounted_price * 100,
                ],
                'quantity' => 1,
            ],
            ],
            'mode' => 'payment',
            'success_url' => $success_url . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancel_url . '?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => [
                'subscription_id' => $subscription->id, // Store the subscription ID in metadata
            ],

        ]);

        // Return the checkout session URL
        return response()->json([
            'url' => $checkoutSession->url
        ]);
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
