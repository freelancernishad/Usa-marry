<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isOwner = $request->user() && $request->user()->id === $this->id;

        // Masking helpers
        $maskEmail = function ($email) {
            $parts = explode('@', $email);
            if (count($parts) !== 2) return null;
            $name = $parts[0];
            $domain = $parts[1];

            return substr($name, 0, 1) . str_repeat('*', strlen($name) - 1) .
                '@' .
                substr($domain, 0, 1) . str_repeat('*', strlen($domain) - 2) . substr($domain, -1);
        };

        $maskPhone = function ($phone) {
            return substr($phone, 0, 2) . str_repeat('*', strlen($phone) - 4) . substr($phone, -2);
        };

        $maskAddress = function ($value) {
            if (!$value) return null;
            return substr($value, 0, 1) . str_repeat('*', max(strlen($value) - 2, 0)) . substr($value, -1);
        };

        // Base user data
        $userData = $this->only([
            'id', 'name', 'email', 'phone','profile_picture', 'gender', 'dob', 'religion', 'caste',
            'sub_caste', 'marital_status', 'height', 'disability', 'blood_group',
            'disability_issue', 'family_location', 'grew_up_in', 'hobbies', 'mother_tongue',
            'profile_created_by', 'verified', 'profile_completion', 'account_status',
            'created_at', 'updated_at'
        ]);

        // Mask sensitive data if not the owner
        if (!$isOwner) {
            $userData['email'] = $userData['email'] ? $maskEmail($userData['email']) : null;
            $userData['phone'] = $userData['phone'] ? $maskPhone($userData['phone']) : null;
            $userData['family_location'] = $userData['family_location'] ? $maskAddress($userData['family_location']) : null;
        }

        // Age
        $userData['age'] = $this->age;

        // Profile fields
        $profileFields = [
            'user_id', 'about', 'highest_degree', 'institution', 'occupation',
            'annual_income', 'employed_in', 'father_status', 'mother_status',
            'siblings', 'family_type', 'family_values', 'financial_status', 'diet',
            'drink', 'smoke', 'country', 'state', 'city', 'resident_status',
            'has_horoscope', 'rashi', 'nakshatra', 'manglik', 'show_contact', 'visible_to'
        ];

        $profileData = $this->profile ? $this->profile->only($profileFields) : array_fill_keys($profileFields, null);

        // Mask address fields in profile if not owner
        if (!$isOwner) {
            foreach (['country', 'state', 'city'] as $field) {
                if (!empty($profileData[$field])) {
                    $profileData[$field] = $maskAddress($profileData[$field]);
                }
            }
        }


            // Load activeSubscription only if it's the owner
             $userData['active_subscription'] = $this->activeSubscription
                ? new SubscriptionResource($this->activeSubscription)
                : null;


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
