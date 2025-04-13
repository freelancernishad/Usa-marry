<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Profile;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load(['profile', 'photos', 'partnerPreference']);
        return response()->json($user);
    }

    public function updateBasicInfo(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            // Required personal details
            'name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'dob' => 'nullable|date|before:-18 years',
            'phone' => 'nullable|numeric|digits:10',

            // Religious and background information
            'religion' => 'required|string|max:255',
            'caste' => 'required|string|max:255',
            'sub_caste' => 'nullable|string|max:255',
            'marital_status' => 'required|string|in:Never Married,Divorced,Widowed,Awaiting Divorce',

            // Physical attributes
            'height' => 'required|numeric|between:100,250',
            'disability' => 'nullable|boolean',
            'mother_tongue' => 'required|string|max:255',

            // Family information
            'father_status' => 'nullable|string|max:255',
            'mother_status' => 'nullable|string|max:255',
            'siblings' => 'nullable|integer|min:0',
            'family_type' => 'nullable|string|in:Nuclear,Joint,Other',
            'family_values' => 'nullable|string|in:Traditional,Moderate,Liberal',
            'financial_status' => 'nullable|string|in:Affluent,Upper Middle Class,Middle Class,Lower Middle Class',

            // Lifestyle
            'diet' => 'required|string|in:Vegetarian,Eggetarian,Non-Vegetarian,Vegan',
            'drink' => 'required|string|in:No,Occasionally,Yes',
            'smoke' => 'required|string|in:No,Occasionally,Yes',

            // Education and career
            'highest_degree' => 'required|string|max:255',
            'institution' => 'nullable|string|max:255',
            'occupation' => 'required|string|max:255',
            'annual_income' => 'nullable|string|max:255',
            'employed_in' => 'nullable|string|in:Government,Private,Business,Self-Employed,Not Working',

            // Location
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'resident_status' => 'nullable|string|in:Citizen,Permanent Resident,Temporary Resident',

            // Horoscope
            'has_horoscope' => 'nullable|boolean',
            'rashi' => 'nullable|string|max:255',
            'nakshatra' => 'nullable|string|max:255',
            'manglik' => 'nullable|string|in:Yes,No,Partial',

            // Profile settings
            'profile_created_by' => 'nullable|string|in:Self,Parent,Sibling,Relative,Friend',
            'show_contact' => 'nullable|boolean',
            'visible_to' => 'nullable|string|in:All,My Community,My Matches',
            'about' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update user table fields
        $user->update($request->only([
            'name', 'gender', 'dob', 'phone', 'religion',
            'caste', 'sub_caste', 'marital_status', 'height',
            'disability', 'mother_tongue', 'profile_created_by'
        ]));

        // Update or create profile with remaining fields
        $profileData = $request->except([
            'name', 'gender', 'dob', 'phone', 'religion',
            'caste', 'sub_caste', 'marital_status', 'height',
            'disability', 'mother_tongue', 'profile_created_by'
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        updateProfileCompletion($user, 'basic_info');

        return response()->json([
            'message' => 'Basic info updated successfully',
            'profile_completion' => $user->profile_completion,
            'user' => $user->load('profile')
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            // User model fields
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'sometimes|required|numeric|digits:10',
            'gender' => 'sometimes|required|in:Male,Female,Other',
            'dob' => 'sometimes|required|date|before:-18 years',
            'religion' => 'sometimes|required|string|max:255',
            'caste' => 'sometimes|required|string|max:255',
            'sub_caste' => 'nullable|string|max:255',
            'marital_status' => 'sometimes|required|string|in:Never Married,Divorced,Widowed,Awaiting Divorce',
            'height' => 'sometimes|required|numeric|between:100,250',
            'disability' => 'nullable|boolean',
            'mother_tongue' => 'sometimes|required|string|max:255',
            'profile_created_by' => 'nullable|string|in:Self,Parent,Sibling,Relative,Friend',
            'account_status' => 'sometimes|in:Active,Suspended,Deleted',

            // Profile model fields
            'about' => 'nullable|string|max:1000',
            'highest_degree' => 'sometimes|required|string|max:255',
            'institution' => 'nullable|string|max:255',
            'occupation' => 'sometimes|required|string|max:255',
            'annual_income' => 'nullable|string|max:255',
            'employed_in' => 'nullable|string|in:Government,Private,Business,Self-Employed,Not Working',
            'father_status' => 'nullable|string|max:255',
            'mother_status' => 'nullable|string|max:255',
            'siblings' => 'nullable|integer|min:0',
            'family_type' => 'nullable|string|in:Nuclear,Joint,Other',
            'family_values' => 'nullable|string|in:Traditional,Moderate,Liberal',
            'financial_status' => 'nullable|string|in:Affluent,Upper Middle Class,Middle Class,Lower Middle Class',
            'diet' => 'sometimes|required|string|in:Vegetarian,Eggetarian,Non-Vegetarian,Vegan',
            'drink' => 'sometimes|required|string|in:No,Occasionally,Yes',
            'smoke' => 'sometimes|required|string|in:No,Occasionally,Yes',
            'country' => 'sometimes|required|string|max:255',
            'state' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'resident_status' => 'nullable|string|in:Citizen,Permanent Resident,Temporary Resident',
            'has_horoscope' => 'nullable|boolean',
            'rashi' => 'nullable|string|max:255',
            'nakshatra' => 'nullable|string|max:255',
            'manglik' => 'nullable|string|in:Yes,No,Partial',
            'show_contact' => 'nullable|boolean',
            'visible_to' => 'nullable|string|in:All,My Community,My Matches',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update user fields
        $userFields = $request->only([
            'name', 'email', 'phone', 'gender', 'dob', 'religion', 'caste',
            'sub_caste', 'marital_status', 'height', 'disability', 'mother_tongue',
            'profile_created_by', 'account_status'
        ]);

        if (!empty($userFields)) {
            $user->update($userFields);
        }

        // Update profile fields
        $profileFields = $request->except([
            'name', 'email', 'phone', 'gender', 'dob', 'religion', 'caste',
            'sub_caste', 'marital_status', 'height', 'disability', 'mother_tongue',
            'profile_created_by', 'account_status', 'password', 'verified',
            'profile_completion', 'email_verified_at', 'email_verification_hash',
            'otp', 'otp_expires_at'
        ]);

        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileFields
        );

        updateProfileCompletion($user, 'profile');

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile_completion' => $user->profile_completion,
            'user' => $user,
            'profile' => $profile
        ]);
    }


}
