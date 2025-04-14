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

        $query = User::where('gender', $oppositeGender)
            ->where('account_status', 'Active')
            ->where('id', '!=', $user->id);

        // Apply partner preferences if they exist
        if ($user->partnerPreference) {
            $prefs = $user->partnerPreference;

            // Age filter (less strict in relaxed mode)
            if ($prefs->age_min || $prefs->age_max) {
                $minAge = $prefs->age_min ?? 18;
                $maxAge = $prefs->age_max ?? 99;

                if ($strictMode) {
                    $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge]);
                } else {
                    // Allow 5 years flexibility in relaxed mode
                    $query->whereRaw(
                        "TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN ? AND ?",
                        [max(18, $minAge - 5), $maxAge + 5]
                    );
                }
            }

            // Height filter (optional in relaxed mode)
            if ($strictMode && ($prefs->height_min || $prefs->height_max)) {
                $minHeight = $prefs->height_min ?? 100;
                $maxHeight = $prefs->height_max ?? 250;
                $query->whereBetween('height', [$minHeight, $maxHeight]);
            }

            // Religion filter (required)
            if ($prefs->religion) {
                $query->where('religion', $prefs->religion);

                // Caste filter (optional in relaxed mode)
                if ($strictMode && $prefs->caste) {
                    $query->where('caste', $prefs->caste);
                }
            }

            // Other filters only in strict mode
            if ($strictMode) {
                if ($prefs->marital_status) {
                    $query->where('marital_status', $prefs->marital_status);
                }

                if ($prefs->education) {
                    $query->whereHas('profile', fn($q) => $q->where('highest_degree', $prefs->education));
                }

                if ($prefs->occupation) {
                    $query->whereHas('profile', fn($q) => $q->where('occupation', $prefs->occupation));
                }

                if ($prefs->country) {
                    $query->whereHas('profile', fn($q) => $q->where('country', $prefs->country));
                }
            }
        }

        // Exclude already matched/rejected users
        $existingMatches = $user->matches()->pluck('matched_user_id');
        $query->whereNotIn('id', $existingMatches);

        // Calculate match score and order by it
        $query->selectRaw('users.*,
            CASE
                WHEN religion = ? THEN 20 ELSE 0 END +
            -- Add other scoring criteria here
            profile_completion * 0.1 AS match_score',
            [$user->partnerPreference->religion ?? '']
        )->orderByDesc('match_score');

        return $query;
    }



    public function showMatch(User $matchedUser)
    {
        $user = Auth::user();

        // Check if this is a valid potential match
        $isValidMatch = $this->findPotentialMatches($user)
            ->where('id', $matchedUser->id)
            ->exists();

        if (!$isValidMatch) {
            return response()->json(['message' => 'User not found in your matches'], 404);
        }

        // Load match data with more details
        $matchedUser->load([
            'profile',
            'photos',
            'partnerPreference'
        ]);

        // Calculate match percentage
        $matchPercentage = $this->calculateMatchPercentage($user, $matchedUser);

        return response()->json([
            'user' => $matchedUser,
            'match_percentage' => $matchPercentage
        ]);
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
}
