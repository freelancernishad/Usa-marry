<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\TokenBlacklist;
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


 function updateProfileCompletion(User $user, $section)
    {
        $completion = $user->profile_completion;

        // Define completion percentages for each section
        $sections = [
            'basic_info' => 30,
            'profile' => 40,
            'photos' => 20,
            'partner_preference' => 10
        ];

        if (!isset($sections[$section])) {
            return;
        }

        // Only add if not already completed
        if (($completion & $sections[$section]) === 0) {
            $user->profile_completion += $sections[$section];
            $user->save();
        }
    }
