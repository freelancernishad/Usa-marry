<?php

namespace App\Http\Controllers\Api\User\UserManagement;

use App\Models\User;
use App\Models\Photo;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\PartnerPreference;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    /**
     * Get the authenticated user's profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        $user = Auth::user(); // Retrieve the authenticated user
        return response()->json($user);
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Retrieve the authenticated user

        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'profile_picture' => 'sometimes|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update user's profile with validated data
        $user->update($request->only(['name']));


            // Handle profile picture upload if provided
    if ($request->hasFile('profile_picture')) {
        try {
            $filePath = $user->saveProfilePicture($request->file('profile_picture'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload profile picture: ' . $e->getMessage(),
            ], 500);
        }
    }


        return response()->json($user);
    }



    public function importUsersFromAPI()
    {
        $response = Http::get('http://192.168.0.129:8000/api/UserFullList');

        if ($response->failed()) {
            return response()->json(['message' => 'API call failed.'], 500);
        }

        $users = $response->json('users');

        foreach ($users as $userData) {
            // Insert user
            $user = User::updateOrCreate(
                ['email' => $userData['email']], // unique identifier
                [
                    'name' => $userData['name'] ?? null,
                    'email' => $userData['email'] ?? null,
                    'password' => $userData['password'] ?? bcrypt('12345678'),
                    'phone' => $userData['mobile_number'] ?? null,
                    'gender' => $userData['gender'] ?? "Male",
                    'dob' => $userData['date_of_birth'] ?? null,
                    'religion' => $userData['religion'] ?? null,
                    'caste' => $userData['community'] ?? null,
                    'sub_caste' => $userData['sub_community'] ?? null,
                    'marital_status' => $userData['marital_status'] ?? null,
                    'height' => $userData['height'] ?? null,
                    'blood_group' => $userData['blood_group'] ?? null,
                    'disability_issue' => $userData['disability'] ?? null,
                    'family_location' => $userData['family_location'] ?? null,
                    'mother_tongue' => $userData['mother_tongue'] ?? null,
                    'profile_created_by' => $userData['profile_created_by'] ?? null,
                    'verified' => true,
                    'profile_completion' => 100,
                    'account_status' => $userData['status'] ?? 'active',
                    'email_verified_at' => $userData['email_verified_at'] ?? now(),
                    'email_verification_hash' => $userData['email_verification_hash'] ?? null,
                    'otp' => $userData['otp'] ?? null,
                    'otp_expires_at' => $userData['otp_expires_at'] ?? null,
                    'hobbies' => [],
                ]
            );

            // Insert profile
            Profile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'about' => $userData['about_myself'] ?? '',
                    'highest_degree' => $userData['highest_qualification'] ?? null,
                    'institution' => $userData['college_name'] ?? null,
                    'occupation' => $userData['profession'] ?? null,
                    'annual_income' => $userData['monthly_income'] ?? null,
                    'employed_in' => $userData['working_sector'] ?? null,
                    'father_status' => $userData['father_occupation'] ?? null,
                    'mother_status' => $userData['mother_occupation'] ?? null,
                    'siblings' => $userData['total_siblings'] ?? null,
                    'family_type' => $userData['family_type'] ?? null,
                    'family_values' => $userData['family_values'] ?? null,
                    'diet' => $userData['diet'] ?? null,
                    'drink' => $userData['drinking'] ?? null,
                    'smoke' => $userData['smoking'] ?? null,
                    'country' => $userData['living_country'] ?? null,
                    'state' => $userData['state'] ?? null,
                    'city' => $userData['city_living_in'] ?? null,
                    'resident_status' => $userData['currently_living_in'] ?? null,
                ]
            );

            // Insert partner preference
            PartnerPreference::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'age_min' => explode('-', $userData['partner_age'] ?? '18-99')[0] ?? null,
                    'age_max' => explode('-', $userData['partner_age'] ?? '18-99')[1] ?? null,
                    'marital_status' => $userData['partner_marital_statuses'] ?? [],
                    'religion' => $userData['partner_religions'] ?? [],
                    'caste' => $userData['partner_communities'] ?? [],
                    'mother_tongue' => $userData['partner_mother_tongues'] ?? [],
                    'occupation' => $userData['partner_professions'] ?? [],
                    'education' => $userData['partner_qualifications'] ?? [],
                    'country' => $userData['partner_countries'] ?? [],
                    'state' => $userData['partner_states'] ?? [],
                    'city' => $userData['partner_cities'] ?? [],
                    'family_type' => [], // Optional if not provided
                ]
            );

            // 4. Save images to Photo table (first one as primary)
                if (!empty($userData['user_images']) && is_array($userData['user_images'])) {
                    foreach ($userData['user_images'] as $index => $image) {
                        Photo::updateOrCreate(
                            [
                                'user_id' => $user->id,
                                'path' => $image['image_path'],
                            ],
                            [
                                'is_primary' => $index === 0,
                                'is_approved' => $image['status'] === 'approved',
                            ]
                        );
                    }
                }







        }

        return response()->json(['message' => 'All users imported successfully.']);
    }


}
