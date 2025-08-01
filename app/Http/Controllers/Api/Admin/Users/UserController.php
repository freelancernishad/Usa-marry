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


 // ✅ Ban user
    public function ban($id)
    {
        $user = User::findOrFail($id);

        if ($user->banned_at) {
            return response()->json(['message' => 'User is already banned.'], 400);
        }

        $user->banned_at = now();
        $user->save();

        return response()->json(['message' => 'User has been banned successfully.']);
    }

    // ✅ Unban user
    public function unban($id)
    {
        $user = User::findOrFail($id);

        if (!$user->banned_at) {
            return response()->json(['message' => 'User is not banned.'], 400);
        }

        $user->banned_at = null;
        $user->save();

        return response()->json(['message' => 'User has been unbanned successfully.']);
    }

    // ✅ Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['message' => 'User has been deleted successfully.']);
    }



    public function toggleTopProfile($id)
    {
        $user = User::findOrFail($id);
        $user->is_top_profile = !$user->is_top_profile;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->is_top_profile
                ? 'User added to Top Bride & Groom list.'
                : 'User removed from Top Bride & Groom list.',
            'is_top_profile' => $user->is_top_profile,
        ]);
    }

    public function topProfiles()
    {
    
        $users = User::where('is_top_profile', true)->latest()->get();

        return UserResource::collection($users);
    }


}
