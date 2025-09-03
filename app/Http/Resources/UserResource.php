<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UserConnection;
use App\Models\ContactView;
use App\Models\User;
use App\Models\PhotoRequest;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $authUser = $request->user();
        $isAdmin = auth()->guard('admin')->check();
        $isOwner = $authUser && $authUser->id === $this->id;

        $contactViewed = false;
        $connectionRequestStatus = null; // I sent
        $receivedConnectionRequestStatus = null; // I received
        $isPhotoRequestSent = false;
        $isPhotoRequestReceived = false;

        if ($authUser && $authUser->id !== $this->id) {

            // Check if contact has been viewed
            $contactViewed = ContactView::where('user_id', $authUser->id)
                ->where('contact_user_id', $this->id)
                ->exists();

            // Check if connection request sent by auth user
            $connection = UserConnection::where('user_id', $authUser->id)
                ->where('connected_user_id', $this->id)
                ->first();
            $connectionRequestStatus = $connection ? $connection->status : null;

            // Check if connection request received by auth user
            $receivedConnection = UserConnection::where('user_id', $this->id)
                ->where('connected_user_id', $authUser->id)
                ->first();
            $receivedConnectionRequestStatus = $receivedConnection ? $receivedConnection->status : null;

            // Check if photo request sent by auth user
            $isPhotoRequestSent = PhotoRequest::where('sender_id', $authUser->id)
                ->where('receiver_id', $this->id)
                ->exists();

            // Check if photo request received by auth user
            $isPhotoRequestReceived = PhotoRequest::where('sender_id', $this->id)
                ->where('receiver_id', $authUser->id)
                ->exists();
        }

        // Masking helpers
        $maskEmail = function ($email) {
            $parts = explode('@', $email);
            if (count($parts) !== 2) return null;
            $name = $parts[0];
            $domain = $parts[1];
            return substr($name, 0, 1) . str_repeat('*', strlen($name) - 1) .
                '@' . substr($domain, 0, 1) . str_repeat('*', strlen($domain) - 2) . substr($domain, -1);
        };

        $maskPhone = fn($phone) => $phone ? substr($phone, 0, 2) . str_repeat('*', strlen($phone) - 4) . substr($phone, -2) : null;
        $maskAddress = fn($value) => $value ? substr($value, 0, 1) . str_repeat('*', max(strlen($value) - 2, 0)) . substr($value, -1) : null;
        $maskWhatsapps = fn($whatsapps) => $whatsapps ? (substr($whatsapps, 0, 2) . str_repeat('*', strlen($whatsapps) - 4) . substr($whatsapps, -2)) : null;

        // Base user data
        $userData = $this->only([
            'id', 'profile_id', 'name', 'email', 'phone','whatsapps', 'profile_picture', 'gender', 'dob', 'religion', 'caste',
            'sub_caste', 'marital_status', 'height', 'disability', 'blood_group',
            'disability_issue', 'family_location', 'grew_up_in', 'hobbies', 'mother_tongue',
            'profile_created_by', 'verified', 'profile_completion', 'account_status','is_top_profile',
            'created_at', 'updated_at'
        ]);

        if (!$isOwner && !$contactViewed && !$isAdmin) {
            $userData['email'] = $maskEmail($userData['email']);
            $userData['phone'] = $maskPhone($userData['phone']);
            $userData['family_location'] = $maskAddress($userData['family_location']);
            $userData['whatsapps'] = $maskWhatsapps($userData['whatsapps']);
        }

        $userData['age'] = $this->age ?? null;

        // Profile fields
        $profileFields = [
            'user_id', 'about', 'highest_degree', 'institution', 'occupation',
            'annual_income', 'employed_in', 'father_status', 'mother_status',
            'siblings', 'family_type', 'family_values', 'financial_status', 'diet',
            'drink', 'smoke', 'country', 'state', 'city', 'resident_status',
            'has_horoscope', 'rashi', 'nakshatra', 'manglik', 'show_contact', 'visible_to'
        ];

        $profileData = $this->profile ? $this->profile->only($profileFields) : array_fill_keys($profileFields, null);

        // Active subscription
        $userData['active_subscription'] = $isOwner && $this->activeSubscription
            ? new SubscriptionResource($this->activeSubscription)
            : null;

        // Match percentage (only for owner)
        $matchPercentage = null;
        if ($isOwner) {
            $matchedUser = User::find($this->id);
            $matchPercentage = calculateMatchPercentageAllFields($authUser, $matchedUser);
        }

        return array_merge(
            $userData,
            $profileData,
            [
                'photos' => $this->visiblePhotos() ?? [],
                'partner_preference' => $this->partnerPreference ?? null,
                'connection_request_Status' => $connectionRequestStatus, // I sent
                'received_connection_status' => $receivedConnectionRequestStatus, // I received
                'contact_viewed' => $contactViewed,
                'match_percentage' => $matchPercentage,
                'plan_name' => $this->plan_name,
                'photos_locked' => $this->photos_locked,
                'is_photo_request_sent' => $isPhotoRequestSent,
                'is_photo_request_received' => $isPhotoRequestReceived,
            ]
        );
    }
}
