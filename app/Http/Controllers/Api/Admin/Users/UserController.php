<?php

namespace App\Http\Controllers\Api\Admin\Users;

use Locale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use libphonenumber\PhoneNumberUtil;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use libphonenumber\PhoneNumberFormat;
use App\Http\Resources\UserPaginationResource;

class UserController extends Controller
{



   public function updateCountryFromgrewUp()
{
     $users = \App\Models\User::with('profile')
        ->where('phone', '+8801711111111')
        ->get();

    foreach ($users as $user) {
        try {
            $grewUpIn = $user->grew_up_in;

            if (!$grewUpIn) continue;

            // Multiple country দিলে প্রথম country নিবে
            $countryList = explode(',', $grewUpIn);
            $country = trim($countryList[0]); // প্রথম country clean করে নিবে

            $user->profile->update([
                'country' => $country,
                'state'   => null,
                'city'    => null
            ]);

        } catch (\Exception $e) {
            \Log::warning("Invalid grew_up_in for user_id {$user->id}");
            continue;
        }
    }

    return response()->json([
        'message' => 'Country updated successfully from grew_up_in field.'
    ]);
}



    public function updateCountryFromPhone()
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        // Region code → country name map
        $regionMap = CountryCodes();

        $users = \App\Models\User::whereNull('family_location')->whereNotNull('phone')->get();

        foreach ($users as $user) {
            $phone = $user->phone;
            if (empty($phone)) continue;

            try {
                $numberProto = $phoneUtil->parse($phone, null);
                $regionCode = $phoneUtil->getRegionCodeForNumber($numberProto); // e.g., BD

                if ($regionCode && isset($regionMap[$regionCode])) {
                    $countryName = $regionMap[$regionCode];
                    Log::info("User ID {$user->id}: Phone {$phone} → Region {$regionCode} → Country {$countryName}");

                    $user->profile->update(['country' => $countryName,'state' => null, 'city' => null]);

                    // if ($user->country !== $countryName) {
                    //     $user->update(['country' => $countryName]);
                    // }
                }

            } catch (\libphonenumber\NumberParseException $e) {
                \Log::warning("Invalid phone for user_id {$user->id}: {$phone}");
                continue;
            }
        }

        return response()->json([
            'message' => 'Country updated successfully from phone numbers (no Locale class needed).'
        ]);
    }






    public function updateLocationFromFamily()
    {
        // Get all users with family_location not null
        $users = \App\Models\User::whereNotNull('family_location')->get();
        // return response()->json([
        //     'total_users' => $users,
        // ]);

        foreach ($users as $user) {

            $family = $user->family_location;

            // Default values (optional, keep existing if empty)
            $city    = $user->city;
            $state   = $user->state;
            $country = $user->country;

            if (!empty($family)) {
                // Split by comma and trim spaces
                $parts = array_map('trim', explode(',', $family));
                $count = count($parts);

                if ($count === 3) {
                    // Format: City, State, Country
                    $city    = $parts[0];
                    $state   = $parts[1];
                    $country = $parts[2];
                } elseif ($count === 2) {
                    // Format: City, Country
                    $city    = $parts[0];
                    $state   = null;       // optional
                    $country = $parts[1];
                } elseif ($count === 1) {
                    // Format: Only City
                    $city = null;
                    $state   = null;
                    $country    = $parts[0];
                } else {
                    // Unexpected format, skip and log
                    \Log::warning("Invalid family_location format for user_id {$user->id}: {$family}");
                    continue; // skip this user
                }
            }

            // Update user record
            $user->profile->update([
                'city'    => $city,
                'state'   => $state,
                'country' => $country,
            ]);
        }

        return response()->json([
            'message' => 'All users location updated successfully from family_location.'
        ]);
    }





    public function destroyWithRelations($id)
    {
        $user = User::with([
            'profile',
            'partnerPreference',
            'photos',
            'profileVisit',
            'matches',
            'reverseMatches',
            'subscriptions',
            'blockedUsers',
            'reportsFiled',
            'connections',
            'connectedUsers',
            'sentPhotoRequests',
            'receivedPhotoRequests',
            'loginLogs'
        ])->findOrFail($id);

        // সব রিলেশন ডিলিট করা
        $user->profile()?->delete();
        $user->partnerPreference()?->delete();
        $user->photos()->delete();
        $user->profileVisit()?->delete();
        $user->matches()->delete();
        $user->reverseMatches()->delete();
        $user->subscriptions()->delete();
        $user->blockedUsers()->delete();
        $user->reportsFiled()->delete();
        $user->connections()->delete();
        $user->connectedUsers()->delete();
        $user->sentPhotoRequests()->delete();
        $user->receivedPhotoRequests()->delete();
        $user->loginLogs()->delete();

         // DELETE Notifications
        \App\Models\Notification::where('user_id', $user->id)->delete();

          // Contact Views — NEW
        \App\Models\ContactView::where('user_id', $user->id)->delete();
        \App\Models\ContactView::where('contact_user_id', $user->id)->delete();



        // সবশেষে user delete
        $user->delete();

        return response()->json([
            'message' => 'User and all related data deleted successfully.'
        ]);
    }





    // ✅ All users with optional search and subscriptions loaded
public function index(Request $request)
{
    // Start with base query including eager loading
    $query = User::with(['activeSubscription.plan']);

    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%$search%")
              ->orWhere('profile_id', 'LIKE', "%$search%")
              ->orWhere('email', 'LIKE', "%$search%")
              ->orWhere('id', 'LIKE', "%$search%")
              ->orWhere('marital_status', 'LIKE', "%$search%")
              ->orWhere('religion', 'LIKE', "%$search%")
              ->orWhere('caste', 'LIKE', "%$search%")
              ->orWhereHas('profile', function ($q2) use ($search) {
                  $q2->where('country', 'LIKE', "%$search%")
                     ->orWhere('occupation', 'LIKE', "%$search%")
                     ->orWhere('highest_degree', 'LIKE', "%$search%")
                     ->orWhere('diet', 'LIKE', "%$search%")
                     ->orWhere('drink', 'LIKE', "%$search%")
                     ->orWhere('smoke', 'LIKE', "%$search%");
              });
        });
    }

    // Apply only subscribed users filter
    if ($request->filled('subscribed') && $request->subscribed == 'true') {
        $query->whereHas('subscriptions', function ($q) {
            $q->where('status', 'Success')->where('end_date', '>=', now());
        });
    }

    // ✅ Apply additional filters using helper
    $query = applyFilters($query, $request);

    // Pagination
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
