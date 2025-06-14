<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

function handleGoogleAuth(Request $request)
{
    $validator = Validator::make($request->all(), [
        'access_token' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
        $response = Http::get('https://www.googleapis.com/oauth2/v3/userinfo', [
            'access_token' => $request->access_token,
        ]);

        if ($response->failed() || !isset($response['email'])) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid access token.',
            ], 400);
        }

        $userData = $response->json();
        $user = User::where('email', $userData['email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $userData['name'] ?? explode('@', $userData['email'])[0],
                'email' => $userData['email'],
                'password' => Hash::make(Str::random(16)),
                'email_verified_at' => now(),
                'profile_completion' => 10,
            ]);
        } else {
            $user->update(['email_verified_at' => now()]);
        }

        Auth::login($user);

        try {
            $token = JWTAuth::fromUser($user, ['guard' => 'user']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'gender' => $user->gender,
                'dob' => $user->dob,
                'phone' => $user->phone,
                'email_verified' => $user->hasVerifiedEmail(),
            ],
            'profile_completion' => $user->profile_completion,
            'message' => 'Login successful',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'An error occurred during authentication.',
            'details' => $e->getMessage(),
        ], 500);
    }
}

