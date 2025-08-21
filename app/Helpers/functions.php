<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\ContactView;
use App\Models\TokenBlacklist;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
function updateProfileCompletionWithPercentage(User $user)
{
        // All profile-related fields for percentage calculation
    $allFields = [
        // user table
        'name','phone','whatsapps','gender','dob','religion','caste','sub_caste',
        'marital_status','height','blood_group','disability_issue','family_location',
        'grew_up_in','mother_tongue','profile_created_by','hobbies',

        // profile table
        'about','highest_degree','occupation','annual_income','employed_in',
        'father_status','mother_status','siblings','family_type',
        'financial_status','diet','country','state','city','resident_status',

        // partner preference table
        'age_min', 'age_max', 'height_min', 'height_max',
        'marital_status', 'religion', 'caste', 'education', 'occupation',
        'country', 'family_type', 'state', 'city', 'mother_tongue'
    ];



    $filledCount = 0;
    $missingFields = [];

    foreach ($allFields as $field) {
        $value = null;

        // Check in user table
        if (!empty($user->$field)) {
            $value = $user->$field;
        }
        // Check in profile table
        elseif (!empty($user->profile) && !empty($user->profile->$field)) {
            $value = $user->profile->$field;
        }
        // Check in partner preference table
        elseif (!empty($user->partnerPreference) && !empty($user->partnerPreference->$field)) {
            $value = $user->partnerPreference->$field;
        }

        if (!empty($value)) {
            $filledCount++;
        } else {
            $missingFields[] = $field;
        }
    }

    // Calculate percentage
    $totalFields = count($allFields);
    $percentage = $totalFields > 0 ? round(($filledCount / $totalFields) * 100, 2) : 0;

    // Log results
    Log::info('Missing profile fields:', $missingFields);
    Log::info('Profile completion percentage calculated', [
        'user_id' => $user->id,
        'profile_completion' => $percentage
    ]);

    $user->update([
        'profile_completion' => $percentage
    ]);

    return $percentage;
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



function getMissingSections_old(User $user)
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


function getMissingSections(User $user)
{
    // Define sections and their fields
    $sections = [
        'profile_creation' => ['name', 'email'],
        'personal_information' => ['dob','religion','phone','about'],
        'location_details' => ['country','city','family_location','resident_status','height','diet'],
        'education_career' => ['highest_degree','employed_in','occupation','annual_income'],
        'about_me' => ['about','financial_status','diet','father_status','mother_status','siblings','family_type'],
        'partner_preference' => ['age_min','age_max','height_min','height_max','marital_status','religion','caste','education','occupation','country','family_type','state','city','mother_tongue'],
        'photos' => ['photos'] // adjust your photo fields
    ];

    $totalFields = 0;
    $filledCount = 0;
    $missingSections = [];

    foreach ($sections as $section => $fields) {
        $sectionFilled = false; // flag for at least one field filled

        foreach ($fields as $field) {
            $value = null;


             if ($section === 'photos') {
                $value = $user->photos()->exists();
            }else {

            // Check in user table
            if (!empty($user->$field)) {
                $value = $user->$field;
            }
            // Check in profile table
            elseif (!empty($user->profile) && !empty($user->profile->$field)) {
                $value = $user->profile->$field;
            }
            // Check in partner preference table
            elseif (!empty($user->partnerPreference) && !empty($user->partnerPreference->$field)) {
                $value = $user->partnerPreference->$field;
            }
        }




            if (!empty($value)) {
                $sectionFilled = true;
                $filledCount++;
            }
        }

        $totalFields += count($fields);

        // Mark as missing only if all fields empty
        if (!$sectionFilled) {
            $missingSections[] = $section;
        }
    }

    // Calculate percentage
    $profileCompletion = $totalFields > 0 ? round(($filledCount / $totalFields) * 100, 2) : 0;

    // Update in DB
    $user->update(['profile_completion' => $profileCompletion]);

    // Logs
    Log::info("Profile completion for user {$user->id}: {$profileCompletion}%");
    Log::info("Missing sections for user {$user->id}:", $missingSections);

    return $missingSections;
}



function getNextMissingSection_old(User $user)
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

function getNextMissingSection(User $user)
{
    // Define sections and their fields
    $sections = [
        'profile_creation' => ['name', 'email'],
        'personal_information' => ['dob','religion','phone','about'],
        'location_details' => ['country','city','family_location','resident_status','height','diet'],
        'education_career' => ['highest_degree','employed_in','occupation','annual_income'],
        'about_me' => ['about','financial_status','diet','father_status','mother_status','siblings','family_type'],
        'partner_preference' => ['age_min','age_max','height_min','height_max','marital_status','religion','caste','education','occupation','country','family_type','state','city','mother_tongue'],
        'photos' => ['photos'] // adjust your photo fields
    ];

    foreach ($sections as $section => $fields) {
        $sectionFilled = false; // flag if any field is filled

        foreach ($fields as $field) {
            $value = null;


             if ($section === 'photos') {
                $value = $user->photos()->exists();
            }else {
                if (!empty($user->$field)) {
                    $value = $user->$field;
                } elseif (!empty($user->profile) && !empty($user->profile->$field)) {
                    $value = $user->profile->$field;
                } elseif (!empty($user->partnerPreference) && !empty($user->partnerPreference->$field)) {
                    $value = $user->partnerPreference->$field;
                }
            }



            if (!empty($value)) {
                $sectionFilled = true;
                break; // No need to check rest of fields in this section
            }
        }

        // If no fields filled, this is the next missing section
        if (!$sectionFilled) {
            return $section;
        }
    }

    // All sections complete
    return null;
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

function calculateMatchPercentageAllFields(User $user, User $matchedUser)
{
    $matchDetails = getMatchDetails($user, $matchedUser); // <-- this was missing

    $totalFields = 12; // You can also use: count($matchDetails);
    $matchedCount = 0;

    foreach ($matchDetails as $field => $data) {
        if (!empty($data['matched'])) {
            $matchedCount++;
        }
    }

    return round(($matchedCount / $totalFields) * 100, 1);
}



 function calculateMatchPercentage(User $user, User $matchedUser)
{
    $details = getMatchDetails($user, $matchedUser);

    $fields = [
        'age', 'height', 'religion', 'caste', 'marital_status',
        'education', 'occupation', 'country', 'family_type',
        'state', 'city', 'mother_tongue'
    ];

    $totalConsidered = 0;
    $matchedCount = 0;

    foreach ($fields as $field) {
        if (!isset($details[$field])) {
            continue;
        }

        $you = $details[$field]['you'] ?? 'not provided';

        // Only consider if the user actually set a preference
        if ($you !== 'not provided') {
            $totalConsidered++;
            if ($details[$field]['matched']) {
                $matchedCount++;
            }
        }
    }

    // Avoid division by zero
    if ($totalConsidered === 0) {
        return 100; // If no preferences set, assume perfect match
    }

    return round(($matchedCount / $totalConsidered) * 100);
}


 function sortMatchesWithPercentage($matches, $user, $perPage, $page)
{
    $sorted = $matches->transform(function ($matchedUser) use ($user) {
        $matchedUser->match_percentage = calculateMatchPercentageAllFields($user, $matchedUser);
        return $matchedUser;
    })->sortByDesc('match_percentage')->values();

    return new \Illuminate\Pagination\LengthAwarePaginator(
        $sorted->forPage($page, $perPage),
        $sorted->count(),
        $perPage,
        $page,
        ['path' => url()->current()]
    );
}



     function applyFilters($query, Request $request)
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
    if ($request->gender) {
        $query->where('gender', $request->gender);
    }

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
