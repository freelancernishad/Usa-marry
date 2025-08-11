<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Auth;

use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use App\Mail\OtpNotification;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserLoginResource;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before:-18 years',
            'phone' => 'required|numeric|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'profile_completion' => 10, // Basic info completed
        ]);

        // Generate a JWT token for the newly created user
        try {
            $token = JWTAuth::fromUser($user, ['guard' => 'user']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        // Generate verification URL (if applicable)
        $verify_url = $request->verify_url ?? null; // Optional verify URL from the request

        // Notify user for email verification
        if ($verify_url) {
            Mail::to($user->email)->send(new VerifyEmail($user, $verify_url));
        } else {
            // Generate a 6-digit numeric OTP
            $otp = random_int(100000, 999999); // Generate OTP
            $user->otp = Hash::make($otp); // Store hashed OTP
            $user->otp_expires_at = now()->addMinutes(5); // Set OTP expiration time
            $user->save();

            // Notify user with the OTP
            Mail::to($user->email)->send(new OtpNotification($otp));
        }



        return response()->json([
            'message' => 'User registered successfully',
            'token' => $token,
            'user' =>new UserLoginResource($user),
            'next_step' => 'complete_profile'
        ], 201);
    }

    public function login(Request $request)
    {

        if ($request->access_token) {
            return handleGoogleAuth($request);
        }

        if ($request->identity_token) {
            return handleAppleAuth($request);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $user = Auth::user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserLoginResource($user),
            'profile_completion' => $user->profile_completion,
            'message' => 'Login successful',
        ]);
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout'], 500);
        }
    }



    public function adminLoginUserByEmail(Request $request)
    {
        // Validator দিয়ে ভ্যালিডেশন
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // ইউজার বের করো
        $user = User::where('email', $request->email)->first();

        // সরাসরি token generate করো
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserLoginResource($user),
            'profile_completion' => $user->profile_completion,
            'message' => 'Login successful (admin override)',
        ]);
    }



}
