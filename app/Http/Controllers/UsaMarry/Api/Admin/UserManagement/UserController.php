<?php
namespace App\Http\Controllers\UsaMarry\Api\Admin\UserManagement;

use App\Models\User;
use App\Models\Block;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{




    public function usersWithoutSubscription(Request $request)
    {
        $per_page = $request->per_page == 'all' ? 'all' : 20;

        // Find users who don't have any active subscription
        $usersQuery = User::whereDoesntHave('activeSubscription')
            ->select('id', 'name', 'email', 'phone', 'created_at')
            ->orderBy('id', 'desc');

        $users = $per_page == 'all' ? $usersQuery->get() : $usersQuery->paginate($per_page);

        // -------------------------
        // Create Subscription For Each User Without One
        // -------------------------
        foreach ($usersQuery->get() as $user) {
            \App\Models\Subscription::create([
                'plan_id' => 7,
                'plan_features' => [
                    ['key' => 'send_messages', 'value' => 'unlimited'],
                    ['key' => 'view_contact', 'value' => 50],
                    ['key' => 'standout_profile', 'value' => true],
                    ['key' => 'direct_contact', 'value' => true],
                    ['key' => 'custom', 'value' => 'Basic customer support'],
                ],
                'user_id' => $user->id,
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addMonths(3)->format('Y-m-d'),
                'original_amount' => '0.00',
                'final_amount' => '0.00',
                'coupon_code' => null,
                'discount_amount' => '0.00',
                'discount_percent' => '0.00',
                'amount' => '0.00',
                'payment_method' => 'Stripe Checkout',
                'transaction_id' => (string) \Illuminate\Support\Str::uuid(),
                'status' => 'Success',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Users without subscription fetched and subscriptions created successfully',
            'data' => $users
        ]);
    }

    function subscribedUserNotFound(Request $request)
    {
        $per_page = $request->per_page == 'all' ? 'all' : 20;

        // Find users who have an active subscription but the user record is missing
        $usersQuery = User::whereHas('activeSubscription')
            ->whereNull('name') // Assuming 'name' is a required field for user records
            ->select('id', 'email', 'phone', 'created_at')
            ->orderBy('id', 'desc');

        $users = $per_page == 'all' ? $usersQuery->get() : $usersQuery->paginate($per_page);

        return response()->json([
            'success' => true,
            'message' => 'Subscribed users not found fetched successfully',
            'data' => $users
        ]);
    }




}
