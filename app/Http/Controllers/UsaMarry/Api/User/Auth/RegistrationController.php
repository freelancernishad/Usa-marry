<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Auth;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Mail\OtpNotification;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerifiedConfirmation;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class RegistrationController extends Controller
{
    // Step 1: Account Signup (remains the same as it creates the user)
    // Step 1: Account Signup with OTP verification
    public function accountSignup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required_without:phone|email|unique:users,email',
            'phone' => 'required_without:email|string|unique:users,phone',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'profile_completion' => 10, // Basic info completed
        ]);

        // Generate JWT token
        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        // Generate and send OTP
        $otp = random_int(100000, 999999);
        $user->otp = Hash::make($otp);
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();


        // Send OTP via email
        if ($request->email) {
            Mail::to($user->email)->send(new OtpNotification($otp));
        }

        // if ($request->email) {
        //     Mail::to('nahidahmedsd47@gmail.com')->send(new OtpNotification($otp));
        // }


        // TODO: Add SMS OTP sending for phone

        return response()->json([
            'message' => 'Account created successfully. Please verify with OTP.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'profile_completion' => $user->profile_completion,
                'requires_otp_verification' => true
            ],
            'next_step' => 'verify_otp'
        ], 201);
    }

    // OTP Verification Endpoint
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        // Check if OTP is expired
        if ($user->otp_expires_at && now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['error' => 'OTP has expired'], 400);
        }

        // Verify OTP
        if (!Hash::check($request->otp, $user->otp)) {
            return response()->json(['error' => 'Invalid OTP'], 400);
        }

        // Mark as verified
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->email_verified_at = now();
        $user->save();

            // ✅ Send confirmation email
        if ($user->email) {
            Mail::to($user->email)->send(new OtpVerifiedConfirmation($user));
        }

        return response()->json([
            'message' => 'OTP verified successfully',
            'next_step' => 'profile_creation'
        ]);
    }

    // Resend OTP Endpoint
    public function resendOtp()
    {
        $user = Auth::user();

        // Generate new OTP
        $otp = random_int(100000, 999999);
        $user->otp = Hash::make($otp);
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        // Send OTP via email
        if ($user->email) {
            Mail::to($user->email)->send(new OtpNotification($otp));
        }
        // TODO: Add SMS OTP sending for phone

        return response()->json([
            'message' => 'New OTP sent successfully',
            'otp_expires_at' => $user->otp_expires_at
        ]);
    }

    // Step 2: Profile Creation (updated to use auth)
    public function createProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_created_by' => 'nullable|string',
            'gender' => 'nullable|string|in:Male,Female,Other',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        // Track before update
        $fields = ['profile_created_by', 'gender'];
        $beforeCount = 0;
        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $beforeCount++;
            }
        }

        // Update only if provided
        $user->update(array_filter([
            'profile_created_by' => $request->profile_created_by,
            'gender' => $request->gender,
        ], fn($value) => !is_null($value) && $value !== ''));

        // Track after update
        $afterCount = 0;
        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $afterCount++;
            }
        }

        // Calculate percentage (out of 2 fields)
        $totalFields = count($fields);
        $percentage = ($afterCount / $totalFields) * 100;

        // Only call if something actually changed
        if ($afterCount > $beforeCount) {
            updateProfileCompletionWithPercentage($user, 'profile_creation', $percentage);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'next_step' => 'personal_information',
            'completion_percentage' => $percentage
        ]);
    }

    // Step 3: Personal Information (updated to use auth)
    public function personalInformation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'religion' => 'required|string',
            'community' => 'required|string',
            'country' => 'required|string',
            'email' => 'sometimes|email|unique:users,email,'.Auth::id(),
            'phone' => 'sometimes|string|unique:users,phone,'.Auth::id(),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'dob' => $request->dob,
            'religion' => $request->religion,
            'caste' => $request->community,
            'country' => $request->country,
            'email' => $request->email ?? $user->email,
            'phone' => $request->phone ?? $user->phone,
        ]);

        updateProfileCompletion($user, 'personal_information');

        return response()->json([
            'message' => 'Personal information updated successfully',
            'next_step' => 'location_details'
        ]);
    }

    // Step 4: Location Details (updated to use auth)
    public function locationDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required|string',
            'resident_status' => 'required|string',
            'marital_status' => 'required|string',
            'height' => 'required|numeric',
            'diet' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->update([
            'marital_status' => $request->marital_status,
            'height' => $request->height,
        ]);

        $profileData = [
            'city' => $request->city,
            'resident_status' => $request->resident_status,
            'diet' => $request->diet,
        ];

        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        updateProfileCompletion($user, 'location_details');

        return response()->json([
            'message' => 'Location details updated successfully',
            'next_step' => 'education_career'
        ]);
    }

    // Step 5: Education & Career (updated to use auth)
    public function educationCareer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'highest_degree' => 'required|string',
            'institution' => 'required|string',
            'occupation' => 'required|string',
            'annual_income' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $profileData = [
            'highest_degree' => $request->highest_degree,
            'institution' => $request->institution,
            'occupation' => $request->occupation,
            'annual_income' => $request->annual_income,
        ];

        $user = Auth::user();
        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        updateProfileCompletion($user, 'education_career');

        return response()->json([
            'message' => 'Education & career details updated successfully',
            'next_step' => 'about_me'
        ]);
    }

    // Step 6: About Me (updated to use auth)
    public function aboutMe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'about' => 'required|string|min:50|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['about' => $request->about]
        );

        updateProfileCompletion($user, 'about_me');

        return response()->json([
            'message' => 'Profile completed successfully',
            'profile_completion' => $user->profile_completion,
            'is_complete' => $user->profile_completion >= 100
        ]);
    }

    // Profile completion status (updated to use auth)
    public function getProfileCompletion()
    {
        $user = Auth::user();

        return response()->json([
            'message' => 'Profile completion status retrieved successfully',
            'status' => 'success',
            'profile_completion' => $user->profile_completion,
            'is_complete' => $user->profile_completion >= 100,
            'missing_sections' => getMissingSections($user),
            'next_section' => getNextMissingSection($user)
        ]);
    }




}
