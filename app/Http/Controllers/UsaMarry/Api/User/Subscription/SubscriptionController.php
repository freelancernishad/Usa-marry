<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Subscription;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function plans()
    {
        return response()->json([
            'plans' => [
                [
                    'id' => 'basic',
                    'name' => 'Basic',
                    'price' => 0,
                    'features' => [
                        'Create profile',
                        'Browse limited matches',
                        'Send 5 interests per month'
                    ],
                    'duration' => 'Lifetime'
                ],
                [
                    'id' => 'premium',
                    'name' => 'Premium',
                    'price' => 2999,
                    'features' => [
                        'Unlimited matches browsing',
                        'Unlimited interests',
                        'Priority listing',
                        'See who viewed your profile',
                        'Advanced search filters'
                    ],
                    'duration' => '3 months'
                ],
                [
                    'id' => 'vip',
                    'name' => 'VIP',
                    'price' => 9999,
                    'features' => [
                        'All premium features',
                        'Dedicated relationship manager',
                        'Profile highlighting',
                        'Verified badge',
                        'Exclusive events'
                    ],
                    'duration' => '12 months'
                ]
            ]
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|in:basic,premium,vip',
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string'
        ]);

        $user = Auth::user();

        // Determine plan details
        $plan = $this->getPlanDetails($request->plan_id);

        // Create subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_name' => $plan['name'],
            'start_date' => now(),
            'end_date' => $plan['duration'] === 'Lifetime' ? null : now()->add($plan['duration']),
            'amount' => $plan['price'],
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'status' => 'Success' // In real app, verify payment first
        ]);

        return response()->json([
            'message' => 'Subscription successful',
            'subscription' => $subscription
        ]);
    }

    private function getPlanDetails($planId)
    {
        $plans = [
            'basic' => [
                'name' => 'Basic',
                'price' => 0,
                'duration' => 'Lifetime'
            ],
            'premium' => [
                'name' => 'Premium',
                'price' => 2999,
                'duration' => '3 months'
            ],
            'vip' => [
                'name' => 'VIP',
                'price' => 9999,
                'duration' => '12 months'
            ]
        ];

        return $plans[$planId];
    }

    public function mySubscription()
    {
        $subscription = Auth::user()->activeSubscription;
        return response()->json($subscription);
    }
}
