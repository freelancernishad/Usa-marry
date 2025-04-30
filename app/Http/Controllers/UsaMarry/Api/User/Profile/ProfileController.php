<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Profile;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user()->load(['profile', 'photos', 'partnerPreference']);

        // Using UserResource to transform the data
        $user = new UserResource($user);
        return response()->json($user);
    }


    public function updateBasicInfo(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            // Required personal details
            'name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'phone' => 'nullable|numeric',

            // Religious and background information
            'religion' => 'nullable|string|max:255',
            'caste' => 'nullable|string|max:255',
            'sub_caste' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|in:Never Married,Divorced,Widowed,Awaiting Divorce',

            // Physical attributes
            'height' => 'nullable|numeric|between:100,250',
            'disability' => 'nullable|boolean',
            'mother_tongue' => 'nullable|string|max:255',

            // Family information
            'father_status' => 'nullable|string|max:255',
            'mother_status' => 'nullable|string|max:255',
            'siblings' => 'nullable|integer|min:0',
            'family_type' => 'nullable|string|in:Nuclear,Joint,Other',
            'family_values' => 'nullable|string|in:Traditional,Moderate,Liberal',
            'financial_status' => 'nullable|string|in:Affluent,Upper Middle Class,Middle Class,Lower Middle Class',

            // Lifestyle
            'diet' => 'nullable|string|in:Vegetarian,Eggetarian,Non-Vegetarian,Vegan',
            'drink' => 'nullable|string|in:No,Occasionally,Yes',
            'smoke' => 'nullable|string|in:No,Occasionally,Yes',

            // Education and career
            'highest_degree' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'annual_income' => 'nullable|string|max:255',
            'employed_in' => 'nullable|string|in:Government,Private,Business,Self-Employed,Not Working',

            // Location
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
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
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Update user basic fields
        $user->update($request->only([
            'name', 'gender', 'dob', 'phone', 'religion',
            'caste', 'sub_caste', 'marital_status', 'height',
            'disability', 'mother_tongue', 'profile_created_by'
        ]));

        // Update or create the profile
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
        $user->load('profile'); // Reload with profile




        return response()->json([
            'message' => 'Basic info updated successfully',
            'profile_completion' => $user->profile_completion,
            'user' =>  new UserResource($user)
        ]);

    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            // User model fields
            'name' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'sometimes|nullable|numeric',
            'gender' => 'sometimes|nullable|in:Male,Female,Other',
            'dob' => 'sometimes|nullable|date',
            'religion' => 'sometimes|nullable|string|max:255',
            'caste' => 'sometimes|nullable|string|max:255',
            'sub_caste' => 'nullable|string|max:255',
            'marital_status' => 'sometimes|nullable|string|in:Never Married,Divorced,Widowed,Awaiting Divorce',
            'height' => 'sometimes|nullable|numeric|between:100,250',

            'blood_group' => 'sometimes|nullable|numeric|between:100,250',
            'disability_issue' => 'sometimes|nullable|numeric|between:100,250',
            'family_location' => 'sometimes|nullable|numeric|between:100,250',
            'grew_up_in' => 'sometimes|nullable|numeric|between:100,250',

            // Add hobbies field
            'hobbies' => 'sometimes|nullable|array', // Add validation for hobbies as an array
            'hobbies.*' => 'string|max:255', // Each hobby should be a string with a max length of 255



            'disability' => 'nullable|boolean',
            'mother_tongue' => 'sometimes|nullable|string|max:255',
            'profile_created_by' => 'nullable|string|in:Self,Parent,Sibling,Relative,Friend',
            // 'account_status' => 'sometimes|in:Active,Suspended,Deleted',

            // Profile model fields
            'about' => 'nullable|string|max:1000',
            'highest_degree' => 'sometimes|nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'occupation' => 'sometimes|nullable|string|max:255',
            'annual_income' => 'nullable|string|max:255',
            'employed_in' => 'nullable|string|in:Government,Private,Business,Self-Employed,Not Working',
            'father_status' => 'nullable|string|max:255',
            'mother_status' => 'nullable|string|max:255',
            'siblings' => 'nullable|integer|min:0',
            'family_type' => 'nullable|string|in:Nuclear,Joint,Other',
            'family_values' => 'nullable|string|in:Traditional,Moderate,Liberal',
            'financial_status' => 'nullable|string|in:Affluent,Upper Middle Class,Middle Class,Lower Middle Class',
            'diet' => 'sometimes|nullable|string|in:Vegetarian,Eggetarian,Non-Vegetarian,Vegan',
            'drink' => 'sometimes|nullable|string|in:No,Occasionally,Yes',
            'smoke' => 'sometimes|nullable|string|in:No,Occasionally,Yes',
            'country' => 'sometimes|nullable|string|max:255',
            'state' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'resident_status' => 'nullable|string|in:Citizen,Permanent Resident,Temporary Resident',
            'has_horoscope' => 'nullable|boolean',
            'rashi' => 'nullable|string|max:255',
            'nakshatra' => 'nullable|string|max:255',
            'manglik' => 'nullable|string|in:Yes,No,Partial',
            'show_contact' => 'nullable|boolean',
            'visible_to' => 'nullable|string|in:All,My Community,My Matches',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Update user fields
        $userFields = $request->only([
            'name', 'email', 'phone', 'gender', 'dob', 'religion', 'caste',
            'sub_caste', 'marital_status', 'height', 'disability', 'mother_tongue',
            'profile_created_by',
        ]);


        // If hobbies are provided, update them in the profile
        if ($request->has('hobbies')) {
            $userFields['hobbies'] = $request->hobbies;
        }

        if (!empty($userFields)) {
            $user->update($userFields);
        }

        // Update profile fields
        $profileFields = $request->except([
            'name', 'email', 'phone', 'gender', 'dob', 'religion', 'caste',
            'sub_caste', 'marital_status', 'height', 'blood_group','disability_issue','family_location','grew_up_in', 'disability', 'mother_tongue',
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
            'user' =>  new UserResource($user)

        ]);
    }


}
