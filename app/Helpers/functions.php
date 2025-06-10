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
    Log::info('Current profile completion: ' . $completion);
    Log::info('Section to update: ' . $section);

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
        Log::info('Updated profile completion: ' . $user->profile_completion);
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

        foreach ($allSections as $section => $value) {
            if (($user->profile_completion & $value) === 0) {
                $missing[] = $section;
            }
        }

        return $missing;
    }

