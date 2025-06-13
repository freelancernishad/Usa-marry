<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UserConnection;
use App\Models\ContactView;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $authUser = $request->user();
        $user = $this->user;
        $isOwner = $authUser && $authUser->id === $user->id;

        // Check if contact view already exists
        $contactViewed = false;

        if ($authUser && $authUser->id !== $user->id) {
            $contactViewed = ContactView::where('user_id', $authUser->id)
                ->where('contact_user_id', $user->id)
                ->exists();
        }

        // Check if authenticated user has sent a connection request
        $connectionRequestSent = false;
        if ($authUser && $authUser->id !== $user->id) {
            $connectionRequestSent = UserConnection::where('user_id', $authUser->id)
                ->where('connected_user_id', $user->id)
                ->exists();
        }

        // Masking functions
        $maskEmail = fn($email) => $email ? (substr($email, 0, 1) . str_repeat('*', strpos($email, '@') - 1) . substr($email, strpos($email, '@'))) : null;
        $maskPhone = fn($phone) => $phone ? (substr($phone, 0, 2) . str_repeat('*', strlen($phone) - 4) . substr($phone, -2)) : null;
        $maskAddress = fn($val) => $val ? (substr($val, 0, 1) . str_repeat('*', max(strlen($val) - 2, 0)) . substr($val, -1)) : null;

        // Main user info
        $userData = $user->only([
            'id', 'name', 'email', 'phone', 'profile_picture', 'gender', 'dob', 'religion', 'caste',
            'sub_caste', 'marital_status', 'height', 'disability', 'blood_group',
            'disability_issue', 'family_location', 'grew_up_in', 'hobbies', 'mother_tongue',
            'profile_created_by', 'verified', 'profile_completion', 'account_status',
            'created_at', 'updated_at'
        ]);

        // Mask if not owner and not viewed
        if (!$isOwner && !$contactViewed) {
            $userData['email'] = $maskEmail($userData['email']);
            $userData['phone'] = $maskPhone($userData['phone']);
            $userData['family_location'] = $maskAddress($userData['family_location']);
        }

        $userData['age'] = $user->age;

        // Profile fields
        $profileFields = [
            'user_id', 'about', 'highest_degree', 'institution', 'occupation',
            'annual_income', 'employed_in', 'father_status', 'mother_status',
            'siblings', 'family_type', 'family_values', 'financial_status', 'diet',
            'drink', 'smoke', 'country', 'state', 'city', 'resident_status',
            'has_horoscope', 'rashi', 'nakshatra', 'manglik', 'show_contact', 'visible_to'
        ];

        $profileData = $this->only($profileFields);

        if (!$isOwner && !$contactViewed) {
            foreach (['country', 'state', 'city'] as $field) {
                $profileData[$field] = $maskAddress($profileData[$field]);
            }
        }

        // Subscription only for owner
        $userData['active_subscription'] = $isOwner && $user->activeSubscription
            ? new SubscriptionResource($user->activeSubscription)
            : null;

        return array_merge(
            $userData,
            $profileData,
            [
                'photos' => $this->photos ?? [],
                'partner_preference' => $this->partnerPreference ?? null,
                'connection_request_sent' => $connectionRequestSent,
                'contact_viewed' => $contactViewed, // ✅ New column
            ]
        );
    }
}
