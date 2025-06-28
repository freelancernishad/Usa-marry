<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Match;

use App\Models\User;
use App\Models\UserMatch;
use App\Models\ProfileVisit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserPaginationResource;

class MatchController extends Controller
{

public function getMatches(Request $request)
{
    $user = Auth::user();
    $perPage = $request->per_page ?? 10;

    // Strict mode query
    $query = $this->findPotentialMatches($user, true);

    // Apply filters (strict)
    $query = $this->applyFilters($query, $request);

    $matches = $query
        ->with(['profile', 'photos' => fn($q) => $q->where('is_primary', true)])
        ->paginate($perPage);

    // If no matches, relaxed mode with filters
    if ($matches->isEmpty()) {
        $query = $this->findPotentialMatches($user, false);

        $query = $this->applyFilters($query, $request);

        $matches = $query
            ->with(['profile', 'photos' => fn($q) => $q->where('is_primary', true)])
            ->paginate($perPage);
    }

    return response()->json($matches);
}







   private function findPotentialMatches(User $user, bool $strictMode = true)
{
    $oppositeGender = $user->gender === 'Male' ? 'Female' : 'Male';

    $query = User::query()
    ->with('photos')
        ->where('gender', $oppositeGender)
        ->where('account_status', 'Active')
        ->where('id', '!=', $user->id);

    if ($user->partnerPreference) {
        $prefs = $user->partnerPreference;

        // Age filter
        if ($prefs->age_min || $prefs->age_max) {
            $minAge = $prefs->age_min ?? 18;
            $maxAge = $prefs->age_max ?? 99;

            if ($strictMode) {
                $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge]);
            } else {
                $query->whereRaw(
                    "TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN ? AND ?",
                    [max(18, $minAge - 5), $maxAge + 5]
                );
            }
        }

        // Height filter
        if ($strictMode && ($prefs->height_min || $prefs->height_max)) {
            $minHeight = $prefs->height_min ?? 100;
            $maxHeight = $prefs->height_max ?? 250;
            $query->whereBetween('height', [$minHeight, $maxHeight]);
        }

        // Religion filter
        if ($prefs->religion && is_array($prefs->religion)) {
            $query->whereIn('religion', $prefs->religion);

            // Caste filter
            if ($strictMode && $prefs->caste && is_array($prefs->caste)) {
                $query->whereIn('caste', $prefs->caste);
            }
        }







        if ($strictMode) {
            if ($prefs->marital_status && is_array($prefs->marital_status)) {
                $query->whereIn('marital_status', $prefs->marital_status);
            }

            if ($prefs->education && is_array($prefs->education)) {
                $query->whereHas('profile', fn($q) => $q->whereIn('highest_degree', $prefs->education));
            }

            if ($prefs->occupation && is_array($prefs->occupation)) {
                $query->whereHas('profile', fn($q) => $q->whereIn('occupation', $prefs->occupation));
            }

            if ($prefs->country && is_array($prefs->country)) {
                $query->whereHas('profile', fn($q) => $q->whereIn('country', $prefs->country));
            }




            // ✅ Family type filter (Always apply)
            if ($prefs->family_type && is_array($prefs->family_type)) {
                $query->whereHas('profile', fn($q) => $q->whereIn('family_type', $prefs->family_type));
            }

            // ✅ State filter (Always apply)
            if ($prefs->state && is_array($prefs->state)) {
                $query->whereHas('profile', fn($q) => $q->whereIn('state', $prefs->state));
            }

            // ✅ City filter (Always apply)
            if ($prefs->city && is_array($prefs->city)) {
                $query->whereHas('profile', fn($q) => $q->whereIn('city', $prefs->city));
            }

            // ✅ Mother tongue filter (Always apply)
            if ($prefs->mother_tongue && is_array($prefs->mother_tongue)) {
                $query->whereHas('profile', fn($q) => $q->whereIn('mother_tongue', $prefs->mother_tongue));
            }



        }
    }

    // Exclude already matched/rejected users
    $existingMatches = $user->matches()->pluck('matched_user_id');
    $query->whereNotIn('id', $existingMatches);

    // Calculate match score
    $religions = $user->partnerPreference->religion ?? [];
    $query->select('users.*')
        ->selectRaw(
            "(CASE WHEN religion IN (" . implode(',', array_fill(0, count($religions), '?')) . ") THEN 20 ELSE 0 END + profile_completion * 0.1) AS match_score",
            $religions
        )
        ->orderByDesc('match_score');

    return $query;
}





public function showMatch($userId)
{
    $user = Auth::user();

    // ✅ Check if authenticated user has filled partner preference
    if (!$user->partnerPreference) {
        return response()->json([
            'success' => false,
            'message' => 'Please complete your partner preference before viewing matches.',
        ], 400);
    }

    // ✅ Find matched user
    $matchedUser = User::where('id', $userId)
        ->where('account_status', 'Active')
        ->first();

    if (!$matchedUser) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // ✅ Log profile visit if it's not self
    if ($user->id !== $matchedUser->id) {
        \App\Models\ProfileVisit::create([
            'visitor_id' => $user->id,
            'visited_id' => $matchedUser->id,
        ]);
    }

    // ✅ Load related data
    $matchedUser->load([
        'profile',
        'photos',
        'partnerPreference',
    ]);

    // ✅ Match calculation and details
    $matchPercentage = calculateMatchPercentage($user, $matchedUser);
    $matchDetails = $this->getMatchDetails($user, $matchedUser);

    return response()->json([
        'success' => true,
        'message' => 'Match details retrieved successfully',
        'user' => new \App\Http\Resources\UserResource($matchedUser),
        'match_percentage' => $matchPercentage,
        'match_details' => $matchDetails,
    ]);
}


private function getMatchDetails($user, $matchedUser)
{
    $preferences = $user->partnerPreference;

    $details = [];

    // Age
    $age = $matchedUser->dob ? \Carbon\Carbon::parse($matchedUser->dob)->age : null;
    $details['age'] = [
        'matched' => $age && $preferences->age_min && $preferences->age_max
            ? ($age >= $preferences->age_min && $age <= $preferences->age_max)
            : false,
        'you' => ($preferences->age_min && $preferences->age_max)
            ? "{$preferences->age_min}-{$preferences->age_max}"
            : 'not provided',
        'matched_user' => $age ?? 'not provided',
    ];

    // Height
    $details['height'] = [
        'matched' => $matchedUser->height && $preferences->height_min && $preferences->height_max
            ? ($matchedUser->height >= $preferences->height_min && $matchedUser->height <= $preferences->height_max)
            : false,
        'you' => ($preferences->height_min && $preferences->height_max)
            ? "{$preferences->height_min}-{$preferences->height_max}"
            : 'not provided',
        'matched_user' => $matchedUser->height ?? 'not provided',
    ];

    // Helper function
    $multiCheck = function ($value, $preference) {
        return [
            'matched' => ($value && is_array($preference)) ? in_array($value, $preference) : false,
            'you' => $preference ?: ['not provided'],
            'matched_user' => $value ?? 'not provided',
        ];
    };

    $profile = $matchedUser->profile;

    $details['religion'] = $multiCheck($matchedUser->religion ?? null, $preferences->religion ?? []);
    $details['caste'] = $multiCheck($matchedUser->caste ?? null, $preferences->caste ?? []);
    $details['marital_status'] = $multiCheck($matchedUser->marital_status ?? null, $preferences->marital_status ?? []);
    $details['education'] = $multiCheck($profile->highest_degree ?? null, $preferences->education ?? []);
    $details['occupation'] = $multiCheck($profile->occupation ?? null, $preferences->occupation ?? []);
    $details['country'] = $multiCheck($profile->country ?? null, $preferences->country ?? []);
    $details['family_type'] = $multiCheck($profile->family_type ?? null, $preferences->family_type ?? []);
    $details['state'] = $multiCheck($profile->state ?? null, $preferences->state ?? []);
    $details['city'] = $multiCheck($profile->city ?? null, $preferences->city ?? []);
    $details['mother_tongue'] = $multiCheck($profile->mother_tongue ?? null, $preferences->mother_tongue ?? []);

    return $details;
}









    public function expressInterest(User $matchedUser)
    {
        $user = Auth::user();

        // Check if already matched
        $existingMatch = UserMatch::where('user_id', $user->id)
            ->where('matched_user_id', $matchedUser->id)
            ->first();

        if ($existingMatch) {
            return response()->json(['message' => 'Already expressed interest'], 400);
        }

        // Create new match record
        $match = UserMatch::create([
            'user_id' => $user->id,
            'matched_user_id' => $matchedUser->id,
            'match_score' => calculateMatchPercentage($user, $matchedUser),
            'status' => 'Pending'
        ]);

        // Create interaction record
        $match->interactions()->create([
            'type' => 'Interest'
        ]);

        return response()->json([
            'message' => 'Interest expressed successfully',
            'match' => $match
        ]);
    }

    public function acceptMatch(UserMatch $match)
    {
        if ($match->matched_user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $match->update(['status' => 'Accepted']);

        // Create mutual match if the other user also accepts
        $reverseMatch = UserMatch::firstOrCreate([
            'user_id' => Auth::id(),
            'matched_user_id' => $match->user_id
        ], [
            'match_score' => $match->match_score,
            'status' => 'Accepted'
        ]);

        return response()->json([
            'message' => 'Match accepted successfully',
            'match' => $match,
            'connection' => $reverseMatch
        ]);
    }

    public function rejectMatch(UserMatch $match)
    {
        $user = Auth::user();

        if (!in_array($user->id, [$match->user_id, $match->matched_user_id])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $match->update(['status' => 'Rejected']);

        return response()->json(['message' => 'Match rejected successfully']);
    }


    // New Matches, Match History, Today Matches, My Match, Near Me, More Match


    public function newMatches(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->per_page ?? 10;

        $query = $this->findPotentialMatches($user, false)
            ->where('created_at', '>=', now()->subDays(3)) // "New" users: last 3 days
            ->where('id', '!=', $user->id);

        // Apply reusable filters
        $query = $this->applyFilters($query, $request);

        $matches = $query
            ->with(['profile', 'photos' => fn($q) => $q->where('is_primary', true)])
            ->paginate($perPage);

        return new UserPaginationResource($matches);
    }


// ✅ 2. Match History (still uses UserMatch and matchedUser)
public function matchHistory(Request $request)
{
    $user = Auth::user();
    $perPage = $request->per_page ?? 10;

    $history = UserMatch::with(['matchedUser.profile', 'matchedUser.photos'])
        ->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('matched_user_id', $user->id);
        })
        ->latest()
        ->paginate($perPage);
    return $history = new UserPaginationResource($history);

}


// ✅ 3. Today Matches
public function todaysMatches(Request $request)
{
    $user = Auth::user();
    $perPage = $request->per_page ?? 10;
    $today = now()->toDateString();

    $query = $this->findPotentialMatches($user, false)
        ->whereDate('created_at', $today);

    $query = $this->applyFilters($query, $request);

    $matches = $query
        ->with(['profile', 'photos' => fn($q) => $q->where('is_primary', true)])
        ->paginate($perPage);

    return new UserPaginationResource($matches);
}


// ✅ 4. My Matches
public function myMatches(Request $request)
{
    $user = Auth::user();
    $perPage = $request->per_page ?? 10;

    $query = $this->findPotentialMatches($user, false);

    $query = $this->applyFilters($query, $request);

    $matches = $query->with(['profile', 'photos' => fn($q) => $q->where('is_primary', true)])
                     ->paginate($perPage);

    return new UserPaginationResource($matches);
}




// ✅ 5. Near Me
public function nearMe(Request $request)
{
    $user = Auth::user();
    $perPage = $request->per_page ?? 10;
    $location = $user->profile;

    $query = $this->findPotentialMatches($user, false)
        ->whereHas('profile', function ($q) use ($location) {
            $q->where('city', $location->city ?? '')
              ->orWhere('state', $location->state ?? '')
              ->orWhere('country', $location->country ?? '');
        });

    // Apply common filters
    $query = $this->applyFilters($query, $request);

    $matches = $query->with(['profile', 'photos' => fn($q) => $q->where('is_primary', true)])
                     ->paginate($perPage);

    return new UserPaginationResource($matches);
}



// ✅ 6. More Matches
public function moreMatches(Request $request)
{
    $perPage = $request->per_page ?? 10;
    $user = Auth::user();

    $query = $this->findPotentialMatches($user, false);

    // Apply filters if any
    $query = $this->applyFilters($query, $request);

    $matches = $query->with(['profile', 'photos' => fn($q) => $q->where('is_primary', true)])
                     ->paginate($perPage);

    return new UserPaginationResource($matches);
}





public function getMatchesWithLimit(Request $request)
{
    $user = Auth::user();
    $perPage = $request->per_page ?? 10;

    // Check if the user has a premium account
    $isPremium = $user->account_type === 'premium'; // Assuming 'account_type' is used to distinguish account types

    // Get the correct pagination limit based on the account type
    $limit = $isPremium ? 20 : $perPage;

    // Get three types of matches: New Matches, My Matches, and Premium Matches
    $newMatches = $this->findPotentialMatches($user,false)->where('created_at', '>=', now()->subDays(3)) // Example condition for "new"
                               ->where('id', '!=', $user->id)->limit($perPage)->get();
    $newMatches = collect($newMatches)->sortByDesc(fn($m) => $m->match_percentage);



        $myMatches =  $this->findPotentialMatches($user,false)->limit($perPage)->get();
        $myMatches = collect($myMatches)->sortByDesc(fn($m) => $m->match_percentage);

    $premiumMatches = $isPremium ? $this->findPotentialMatches($user,false)->limit($perPage)->get() : [];
    $premiumMatches = collect($premiumMatches)->sortByDesc(fn($m) => $m->match_percentage);


    // Format the results using UserResource
    return response()->json([
        'status' => 'success',
        'message' => 'Matches retrieved successfully',
        'new_matches' => UserResource::collection($newMatches),
        'my_matches' => UserResource::collection($myMatches),
        'premium_matches' => UserResource::collection($premiumMatches),
    ]);
}





    private function applyFilters($query, Request $request)
{
    // Basic filters with 'all' condition
    $photoVisibility = $request->photo_visibility; // 'all', 'profile_only', etc.
    $maritalStatus = $request->marital_status;     // 'all', 'single', etc.
    $recent = $request->recent;                    // 'all', 'day', 'week', 'month'

    $recentDaysMap = [
        'day' => 1,
        'week' => 7,
        'month' => 30,
    ];
    $recentDays = $recentDaysMap[$recent] ?? null;

    if ($photoVisibility && $photoVisibility !== 'all') {
        $query->where('photo_visibility', $photoVisibility);
    }

    if ($maritalStatus && $maritalStatus !== 'all') {
        $query->where('marital_status', $maritalStatus);
    }

    if ($recent && $recent !== 'all' && $recentDays) {
        $query->where('created_at', '>=', now()->subDays($recentDays));
    }

    // Age filter
    if ($request->has('age_min') || $request->has('age_max')) {
        $minAge = $request->age_min ?? 18;
        $maxAge = $request->age_max ?? 99;
        $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge]);
    }

    // Height filter
    if ($request->has('height_min') || $request->has('height_max')) {
        $minHeight = $request->height_min ?? 100;
        $maxHeight = $request->height_max ?? 250;
        $query->whereBetween('height', [$minHeight, $maxHeight]);
    }

    // Religion & Caste
    if ($request->religion) {
        $query->where('religion', $request->religion);
        if ($request->caste) {
            $query->where('caste', $request->caste);
        }
    }

    // Marital status again if provided differently (optional, but safe)
    if ($request->marital_status) {
        $query->where('marital_status', $request->marital_status);
    }

    // Education
    if ($request->education) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('highest_degree', $request->education);
        });
    }

    // Occupation
    if ($request->occupation) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('occupation', $request->occupation);
        });
    }

    // Country
    if ($request->country) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('country', $request->country);
        });
    }

    // Lifestyle filters: diet, drink, smoke
    if ($request->diet) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('diet', $request->diet);
        });
    }

    if ($request->drink) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('drink', $request->drink);
        });
    }

    if ($request->smoke) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('smoke', $request->smoke);
        });
    }

    return $query;
}






}
