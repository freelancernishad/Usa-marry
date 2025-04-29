<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Fetch user-related data
        $userData = $this->user->only([ // Assuming 'user' relationship is loaded
            'id', 'name', 'email', 'phone', 'gender', 'dob', 'religion', 'caste',
            'sub_caste', 'marital_status', 'height', 'disability', 'blood_group',
            'disability_issue', 'family_location', 'grew_up_in', 'hobbies', 'mother_tongue',
            'profile_created_by', 'verified', 'profile_completion', 'account_status',
            'created_at', 'updated_at'
        ]);

        // Add the user's age to the response
        $userData['age'] = $this->user->age;

        // Define profile-related fields
        $profileFields = [
            'user_id', 'about', 'highest_degree', 'institution', 'occupation',
            'annual_income', 'employed_in', 'father_status', 'mother_status',
            'siblings', 'family_type', 'family_values', 'financial_status', 'diet',
            'drink', 'smoke', 'country', 'state', 'city', 'resident_status',
            'has_horoscope', 'rashi', 'nakshatra', 'manglik', 'show_contact', 'visible_to'
        ];

        // Fetch the profile data or set null for missing profile
        $profileData = $this->only($profileFields);

        return array_merge(
            $userData,
            $profileData,
            [
                'photos' => $this->photos ?? [],
                'partner_preference' => $this->partnerPreference ?? null,
            ]
        );
    }
}
