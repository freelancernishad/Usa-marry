<?php

namespace App\Http\Controllers\Api\Admin\Users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserPaginationResource;

class UserController extends Controller
{
    // ✅ All users with optional search and subscriptions loaded
    public function index(Request $request)
    {
        $query = User::with(['activeSubscription.plan']); // eager load to reduce queries

        // Optional search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%")
                  ->orWhere('id', 'LIKE', "%$search%");
            });
        }

        // Optional filter: only subscribed users
        if ($request->filled('subscribed') && $request->subscribed == 'true') {
            $query->whereHas('subscriptions', function ($q) {
                $q->where('status', 'Success')->where('end_date', '>=', now());
            });
        }

        $users = $query->latest()->paginate($request->input('per_page', 10));

        return new UserPaginationResource($users);
    }

    // ✅ View single user with subscription and profile data
    public function show($id)
    {
        $user = User::with([
            'profile',
            'partnerPreference',
            'photos',
            'activeSubscription.plan'
        ])->findOrFail($id);

        return new UserResource($user);
    }

    // ✅ Show user's current active plan/subscription
    public function showSubscription($id)
    {
        $user = User::with('activeSubscription.plan')->findOrFail($id);

        if (!$user->activeSubscription) {
            return response()->json([
                'message' => 'This user does not have any active subscription.'
            ], 404);
        }

        return response()->json([
            'plan_name' => $user->plan_name,
            'subscription_details' => $user->activeSubscription
        ]);
    }
}
