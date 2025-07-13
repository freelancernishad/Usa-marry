<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\ContactView;
use App\Models\TokenBlacklist;
use App\Helpers\NotificationHelper;
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




function getMatchDetails($user, $matchedUser)
{
    $preferences = $user->partnerPreference;

    if (!$preferences) {
        $preferences = (object) [
            'age_min' => null,
            'age_max' => null,
            'height_min' => null,
            'height_max' => null,
            'religion' => [],
            'caste' => [],
            'marital_status' => [],
            'education' => [],
            'occupation' => [],
            'country' => [],
            'family_type' => [],
            'state' => [],
            'city' => [],
            'mother_tongue' => [],
        ];
    }

    $details = [];

    // Age
    $age = $matchedUser->dob ? \Carbon\Carbon::parse($matchedUser->dob)->age : null;
    $details['age'] = [
        'matched' => $age !== null && $preferences->age_min && $preferences->age_max
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

    // Helper for array-based preferences
    $multiFormat = function ($value, $preferenceArray) {
        return [
            'matched' => $value && is_array($preferenceArray) && in_array($value, $preferenceArray),
            'you' => !empty($preferenceArray) ? implode(', ', $preferenceArray) : 'not provided',
            'matched_user' => $value ?? 'not provided',
        ];
    };

    $profile = $matchedUser->profile ?? (object) [];

    $details['religion'] = $multiFormat($matchedUser->religion ?? null, $preferences->religion ?? []);
    $details['caste'] = $multiFormat($matchedUser->caste ?? null, $preferences->caste ?? []);
    $details['marital_status'] = $multiFormat($matchedUser->marital_status ?? null, $preferences->marital_status ?? []);
    $details['education'] = $multiFormat($profile->highest_degree ?? null, $preferences->education ?? []);
    $details['occupation'] = $multiFormat($profile->occupation ?? null, $preferences->occupation ?? []);
    $details['country'] = $multiFormat($profile->country ?? null, $preferences->country ?? []);
    $details['family_type'] = $multiFormat($profile->family_type ?? null, $preferences->family_type ?? []);
    $details['state'] = $multiFormat($profile->state ?? null, $preferences->state ?? []);
    $details['city'] = $multiFormat($profile->city ?? null, $preferences->city ?? []);
    $details['mother_tongue'] = $multiFormat($profile->mother_tongue ?? null, $preferences->mother_tongue ?? []);

    return $details;
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
            $age = null;
            if ($matchedUser->dob && property_exists($matchedUser->dob, 'age')) {
                $age = $matchedUser->dob->age;
            }
            $minAge = $user->partnerPreference->age_min ?? 18;
            $maxAge = $user->partnerPreference->age_max ?? 99;

            if ($age !== null && $age >= $minAge && $age <= $maxAge) {
                $score += 15;
            } elseif ($age !== null) {
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





     function connectWithUser($connectedUserId)
    {
        $user = Auth::user();

        if ($user->id == $connectedUserId) {
            return response()->json(['message' => 'You cannot send a connection request to yourself.'], 400);
        }

        $connectedUser = User::find($connectedUserId);
        if (!$connectedUser) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $existingConnection = $user->connections()
            ->where('connected_user_id', $connectedUserId)
            ->first();

        if ($existingConnection) {
            switch ($existingConnection->status) {
                case 'pending':
                    return response()->json(['message' => 'Connection request is already pending.'], 400);

                case 'accepted':
                    return response()->json(['message' => 'You are already connected.'], 400);

                case 'disconnected':
                case 'rejected':
                case 'cancelled':
                    $existingConnection->status = 'pending';
                    $existingConnection->save();
// Notify receiver using the 'received' blade (they get a new request)
NotificationHelper::sendUserNotification(
    $connectedUser, // receiver
    "{$user->name} has sent you a connection request again.",
    'Connection Request Re-sent',
    'User',
    $user->id,
    'emails.notification.invitation_received',
     [
        'senderName'     => $user->name,
        'profile_picture'     => $user->profile_picture,
        'senderCode'     => $user->id ?? '',
        'senderLocation' => $user->location ?? '',
        'senderAge'      => $user->age ?? '',
        'senderHeight'   => $user->height ?? '',
        'senderReligion' => $user->religion ?? '',
        'senderCaste'    => $user->caste ?? '',
        'profileUrl'     => "https://usamarry.com/dashboard/profile/$user->id",
        'acceptUrl'      => "https://usamarry.com/dashboard/profile/$user->id",
        'declineUrl'     => "https://usamarry.com/dashboard/profile/$user->id",
        'recipientName'  => $connectedUser->name,
    ]

);

// Notify sender using the 'sent' blade (confirmation that request was sent)
NotificationHelper::sendUserNotification(
    $user, // sender
    "You have re-sent a connection request to {$connectedUser->name}.",
    'Connection Request Re-sent',
    'User',
    $connectedUser->id,
    'emails.notification.invitation_sent',
        [
        'user' => $user,
        'profile_picture' => $connectedUser->profile_picture,
        'connection_user' => $connectedUser,
        'profileUrl' => "https://usamarry.com/dashboard/profile/{$connectedUser->id}",
        'connection_location' => $connectedUser->location ?? 'N/A',
    ]
);

                    return response()->json(['message' => 'Connection request has been re-sent.'], 200);

                case 'blocked':
                    return response()->json(['message' => 'You have blocked this user or have been blocked.'], 400);

                default:
                    return response()->json(['message' => 'Unknown connection status.'], 400);
            }
        }

        // Create new connection request
        $user->connections()->create([
            'connected_user_id' => $connectedUserId,
            'status' => 'pending',
        ]);

// Notify receiver
NotificationHelper::sendUserNotification(
    $connectedUser,
    "{$user->name} has sent you a connection request.",
    'New Connection Request',
    'User',
    $user->id,
    'emails.notification.invitation_received',
         [
        'senderName'     => $user->name,
        'senderCode'     => $user->id ?? '',
        'senderLocation' => $user->location ?? '',
        'senderAge'      => $user->age ?? '',
        'senderHeight'   => $user->height ?? '',
        'senderReligion' => $user->religion ?? '',
        'senderCaste'    => $user->caste ?? '',
        'profile_picture'    => $user->profile_picture ?? '',
        'profileUrl'     => "https://usamarry.com/dashboard/profile/$user->id",
        'acceptUrl'      => "https://usamarry.com/dashboard/profile/$user->id",
        'declineUrl'     => "https://usamarry.com/dashboard/profile/$user->id",
        'recipientName'  => $connectedUser->name,
    ]
);

// Notify sender
NotificationHelper::sendUserNotification(
    $user,
    "You have sent a connection request to {$connectedUser->name}.",
    'Connection Request Sent',
    'User',
    $connectedUser->id,
    'emails.notification.invitation_sent',
        [
        'user' => $user,
        'profile_picture' => $connectedUser->profile_picture,
        'connection_user' => $connectedUser,
        'profileUrl' => "https://usamarry.com/dashboard/profile/{$connectedUser->id}",
        'connection_location' => $connectedUser->location ?? 'N/A',
    ]
);


        return response()->json(['message' => 'Connection request sent successfully.'], 201);
    }
