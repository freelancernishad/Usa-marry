<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\ContactView;
use App\Models\TokenBlacklist;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

function TokenBlacklist($token){
// Get the authenticated user for each guard
    $user = null;
    $guardType = null;

    if (Auth::guard('admin')->check()) {
        $user = Auth::guard('admin')->user();
        $guardType = 'admin';
    } elseif (Auth::guard('user')->check()) {
        $user = Auth::guard('user')->user();
        $guardType = 'user';
    }


    TokenBlacklist::create([
            'token' => $token,
            'user_id' => $user->id,
            'user_type' => $guardType,
            'date' => Carbon::now(),
            ]);
}



function validateRequest(array $data, array $rules)
{
    $validator = Validator::make($data, $rules);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    return null; // Return null if validation passes
}


    // The rest of the helper methods remain the same
function updateProfileCompletion(User $user, $section)
{
    $completion = $user->profile_completion;


    // Define completion percentages for each section
    $sections = [
        'account_signup' => 10,
        'profile_creation' => 15,
        'personal_information' => 20,
        'location_details' => 15,
        'education_career' => 20,
        'about_me' => 10,
        'photos' => 5,
        'partner_preference' => 5,
    ];

    if (!isset($sections[$section])) {
        return;
    }

    // Calculate the total completion value for all sections up to the current one
    $totalCompletion = 0;
    foreach ($sections as $key => $value) {
        $totalCompletion += $value;
        if ($key === $section) {
            break;
        }
    }

    // Only update if the current completion is less than the calculated total
    if ($completion < $totalCompletion) {
        $user->profile_completion = $totalCompletion;
        $user->save();

    }
}


function getMissingSections(User $user)
{
    $allSections = [
        'account_signup' => 10,
        'profile_creation' => 15,
        'personal_information' => 20,
        'location_details' => 15,
        'education_career' => 20,
        'about_me' => 10,
        'photos' => 5,
        'partner_preference' => 5,
    ];

    $missing = [];
    $completion = $user->profile_completion;
    $completedTotal = 0;

    foreach ($allSections as $section => $value) {
        $completedTotal += $value;

        if ($completion < $completedTotal) {
            $missing[] = $section;
        }
    }

    return $missing;
}

function getNextMissingSection(User $user)
{
    $allSections = [
        'account_signup' => 10,
        'profile_creation' => 15,
        'personal_information' => 20,
        'location_details' => 15,
        'education_career' => 20,
        'about_me' => 10,
        'photos' => 5,
        'partner_preference' => 5,
    ];

    $completion = $user->profile_completion;
    $completedTotal = 0;

    foreach ($allSections as $section => $value) {
        $completedTotal += $value;

        if ($completion < $completedTotal) {
            return $section; // first missing = next missing
        }
    }

    return null; // all completed
}






 function calculateMatchPercentage(User $user, User $matchedUser)
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
