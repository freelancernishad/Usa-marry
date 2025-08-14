<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'profile_id' => $this->profile_id,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'phone' => $this->phone,
            'profile_picture' => $this->profile_picture,
            'email_verified' => $this->hasVerifiedEmail(),
            'is_premium' => $this->is_premium,
            'profile_completion' => $this->profile_completion,
            'plan_name' => $this->plan_name,
        ];
    }
}
