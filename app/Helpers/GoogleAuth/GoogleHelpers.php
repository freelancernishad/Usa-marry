<?php


use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\UserLoginResource;
use Illuminate\Support\Facades\Validator;
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
            'user' => new UserLoginResource($user),
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




function handleAppleAuth(Request $request)
{
    $validator = Validator::make($request->all(), [
        'identity_token' => 'required|string',
        'name' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
        // Verify identity token via Apple's public key (optional but recommended)
        $appleToken = $request->identity_token;
        $name = $request->name;
        $appleUserInfo = decodeAppleIdentityToken($appleToken);
        Log::info($appleUserInfo);

        if (!$appleUserInfo || !isset($appleUserInfo['email'])) {
            return response()->json(['error' => 'Invalid Apple token'], 400);
        }

        $user = User::where('email', $appleUserInfo['email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $name ?? explode('@', $appleUserInfo['email'])[0],
                'email' => $appleUserInfo['email'],
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
            'user' => new UserLoginResource($user),
            'profile_completion' => $user->profile_completion,
            'message' => 'Login successful via Apple',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Apple Login failed',
            'details' => $e->getMessage(),
        ], 500);
    }
}


 function decodeAppleIdentityToken($identityToken)
{
    try {
        $tokenParts = explode(".", $identityToken);
        $payload = base64_decode(strtr($tokenParts[1], '-_', '+/'));
        return json_decode($payload, true);
    } catch (\Exception $e) {
        return null;
    }
}
