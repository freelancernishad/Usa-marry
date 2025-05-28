<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Match;

use App\Models\User;
use App\Models\UserMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class MatchController extends Controller
{
    public function getMatches(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->per_page ?? 10;

        // Get base matches with scoring
        $matches = $this->findPotentialMatches($user)
            ->with(['profile', 'photos' => fn($q) => $q->where('is_primary', true)])
            ->paginate($perPage);

        // If no matches found with strict criteria, try relaxed criteria
        if ($matches->isEmpty()) {
            $matches = $this->findPotentialMatches($user, false) // relaxed mode
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
        $matchedUser = User::where('id', $userId)
            ->where('account_status', 'Active')
            ->first();

        if (!$matchedUser) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user = Auth::user();

        // Check if valid match
        // You can technically run the code without this block,
        // but then you won't be checking if the user is a valid match.
        // This means any user ID could be accessed, even if not a match.
        // For security and business logic, it's recommended to keep this check.

        // Load details
        $matchedUser->load([
            'profile',
            'photos',
            'partnerPreference'
        ]);

        // Calculate percentage
        $matchPercentage = $this->calculateMatchPercentage($user, $matchedUser);

        // Compare data
        $matchDetails = $this->getMatchDetails($user, $matchedUser);

        return response()->json([
            'success' => true,
            'message' => 'Match details retrieved successfully',
            'user' => $matchedUser,
            'match_percentage' => $matchPercentage,
            'match_details' => $matchDetails
        ]);
    }





    private function getMatchDetails($user, $matchedUser)
{
    $preferences = $user->partnerPreference;

    $details = [
        'age' => [],
        'height' => [],
        'religion' => [],
        'caste' => [],
        'marital_status' => [],
        'education' => [],
        'occupation' => [],
        'country' => [],
    ];

    // Age
    if ($matchedUser->dob && $preferences->age_min && $preferences->age_max) {
        $age = \Carbon\Carbon::parse($matchedUser->dob)->age;
        $details['age'] = [
            'matched' => $age >= $preferences->age_min && $age <= $preferences->age_max,
            'you' => "{$preferences->age_min}-{$preferences->age_max}",
            'matched_user' => $age,
        ];
    }

    // Height
    if ($matchedUser->height && $preferences->height_min && $preferences->height_max) {
        $details['height'] = [
            'matched' => $matchedUser->height >= $preferences->height_min && $matchedUser->height <= $preferences->height_max,
            'you' => "{$preferences->height_min}-{$preferences->height_max}",
            'matched_user' => $matchedUser->height,
        ];
    }

    // Religion
    if ($preferences->religion) {
        $details['religion'] = [
            'matched' => in_array($matchedUser->religion, $preferences->religion),
            'you' => $preferences->religion,
            'matched_user' => $matchedUser->religion,
        ];
    }

    // Caste
    if ($preferences->caste) {
        $details['caste'] = [
            'matched' => in_array($matchedUser->caste, $preferences->caste),
            'you' => $preferences->caste,
            'matched_user' => $matchedUser->caste,
        ];
    }

    // Marital Status
    if ($preferences->marital_status) {
        $details['marital_status'] = [
            'matched' => in_array($matchedUser->marital_status, $preferences->marital_status),
            'you' => $preferences->marital_status,
            'matched_user' => $matchedUser->marital_status,
        ];
    }

    // Education
    if ($matchedUser->profile && $preferences->education) {
        $details['education'] = [
            'matched' => in_array($matchedUser->profile->highest_degree, $preferences->education),
            'you' => $preferences->education,
            'matched_user' => $matchedUser->profile->highest_degree ?? null,
        ];
    }

    // Occupation
    if ($matchedUser->profile && $preferences->occupation) {
        $details['occupation'] = [
            'matched' => in_array($matchedUser->profile->occupation, $preferences->occupation),
            'you' => $preferences->occupation,
            'matched_user' => $matchedUser->profile->occupation ?? null,
        ];
    }

    // Country
    if ($matchedUser->profile && $preferences->country) {
        $details['country'] = [
            'matched' => in_array($matchedUser->profile->country, $preferences->country),
            'you' => $preferences->country,
            'matched_user' => $matchedUser->profile->country ?? null,
        ];
    }

    return $details;
}








    private function calculateMatchPercentage(User $user, User $matchedUser)
    {
        $score = 0;
        $maxScore = 0;

        // 1. Basic Compatibility (20%)
        $maxScore += 20;
        if ($user->partnerPreference && $user->partnerPreference->religion) {
            if ($user->partnerPreference->religion === $matchedUser->religion) {
                $score += 10;
                if ($user->partnerPreference->caste === $matchedUser->caste) {
                    $score += 10;
                }
            }
        } else {
            $score += 20; // No preference means full points
        }

        // 2. Age Compatibility (15%)
        $maxScore += 15;
        if ($user->partnerPreference && ($user->partnerPreference->age_min || $user->partnerPreference->age_max)) {
            $age = $matchedUser->dob->age;
            $minAge = $user->partnerPreference->age_min ?? 18;
            $maxAge = $user->partnerPreference->age_max ?? 99;

            if ($age >= $minAge && $age <= $maxAge) {
                $score += 15;
            } else {
                $score += max(0, 15 - abs($age - (($minAge + $maxAge) / 2)));
            }
        } else {
            $score += 15;
        }

        // 3. Education & Career (20%)
        $maxScore += 20;
        if ($user->profile && $matchedUser->profile) {
            // Education match
            if ($user->partnerPreference && $user->partnerPreference->education) {
                if ($user->partnerPreference->education === $matchedUser->profile->highest_degree) {
                    $score += 10;
                }
            } else {
                $score += 10;
            }

            // Occupation match
            if ($user->partnerPreference && $user->partnerPreference->occupation) {
                if ($user->partnerPreference->occupation === $matchedUser->profile->occupation) {
                    $score += 10;
                }
            } else {
                $score += 10;
            }
        }

        // 4. Lifestyle (15%)
        $maxScore += 15;
        if ($user->profile && $matchedUser->profile) {
            // Diet
            if ($user->profile->diet === $matchedUser->profile->diet) {
                $score += 5;
            }

            // Drink/Smoke
            if ($user->profile->drink === $matchedUser->profile->drink) {
                $score += 5;
            }
            if ($user->profile->smoke === $matchedUser->profile->smoke) {
                $score += 5;
            }
        }

        // 5. Location (10%)
        $maxScore += 10;
        if ($user->profile && $matchedUser->profile) {
            if ($user->partnerPreference && $user->partnerPreference->country) {
                if ($user->partnerPreference->country === $matchedUser->profile->country) {
                    $score += 10;
                }
            } else {
                $score += 10;
            }
        }

        // 6. Horoscope (10%)
        $maxScore += 10;
        if ($user->profile && $matchedUser->profile) {
            if ($user->profile->has_horoscope && $matchedUser->profile->has_horoscope) {
                // Simple check - in real app you'd use proper astro matching
                if ($user->profile->manglik === $matchedUser->profile->manglik) {
                    $score += 10;
                }
            } else {
                $score += 10;
            }
        }

        // 7. Profile Completeness (10%)
        $maxScore += 10;
        $score += ($matchedUser->profile_completion / 100) * 10;

        return min(100, round(($score / $maxScore) * 100));
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
            'match_score' => $this->calculateMatchPercentage($user, $matchedUser),
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


// ✅ 1. New Matches
public function newMatches(Request $request)
{
    $user = Auth::user();
    $perPage = $request->per_page ?? 10;

    $matches = $this->findPotentialMatches($user,false)
        ->where('created_at', '>=', now()->subDays(3)) // Example condition for "new"
        ->where('id', '!=', $user->id)
        ->paginate($perPage);

    return response()->json($matches);
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

    return response()->json($history);
}


// ✅ 3. Today Matches
public function todaysMatches(Request $request)
{
    $user = Auth::user();
    $perPage = $request->per_page ?? 10;
    $today = now()->toDateString();

    $matches = $this->findPotentialMatches($user,false)
        ->whereDate('created_at', $today)
        ->where('id', '!=', $user->id)
        ->paginate($perPage);

    return response()->json($matches);
}



// ✅ 4. My Matches
public function myMatches(Request $request)
{
    $user = Auth::user();
    $perPage = $request->per_page ?? 10;

    $matches = $this->findPotentialMatches($user,false)
        ->paginate($perPage);

    return response()->json($matches);
}



// ✅ 5. Near Me
public function nearMe(Request $request)
{
    $user = Auth::user();
    $perPage = $request->per_page ?? 10;
    $location = $user->profile;

    $matches = $this->findPotentialMatches($user,false)
        ->whereHas('profile', function ($q) use ($location) {
            $q->where('city', $location->city ?? '')
              ->orWhere('state', $location->state ?? '')
              ->orWhere('country', $location->country ?? '');
        })
        ->paginate($perPage);

    return response()->json($matches);
}


// ✅ 6. More Matches
public function moreMatches(Request $request)
{
    $perPage = $request->per_page ?? 10;

    $user = Auth::user();
    $matches = $this->findPotentialMatches($user,false)->paginate($perPage);

    return response()->json($matches);
}




}
