<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\PhotoRequest;

class MyMatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $authUser = $request->user();

        $isPhotoRequestSent = false;
        
        if ($authUser && $authUser->id !== $this->id) {
            $isPhotoRequestSent = PhotoRequest::where('sender_id', $authUser->id)
                ->where('receiver_id', $this->id)
                ->exists();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
            'about' => $this->profile->about ?? null,
            'hobbies' => $this->hobbies,
            'match_percentage' => null,
            'plan_name' => $this->plan_name,
            'marital_status' => $this->marital_status,
            'religion' => $this->religion,
            'caste' => $this->caste,
            'is_active' => $this->is_active,
            'last_active_at' => $this->last_active_at,
            'mother_tongue' => $this->mother_tongue,
            'height' => $this->height,
            'occupation' => $this->profile->occupation ?? null,
            'highest_degree' => $this->profile->highest_degree ?? null,
            'grew_up_in' => $this->grew_up_in,
            'city' => $this->profile->city ?? null,
            'state' => $this->profile->state ?? null, // Added based on list (city, state, country)
            'country' => $this->profile->country ?? null,
            'photos' => $this->photos, // Using the loaded photos relation
            'profile_picture' => $this->profile_picture,
            'photos_locked' => $this->photos_locked,
            'is_photo_request_sent' => $isPhotoRequestSent,
            'gender' => $this->gender,
            'connection_request_Status' => $this->connection_request_Status,
            'received_connection_status' => $this->received_connection_status,
        ];
    }
}
