<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
           'id' => $this->id,
            'user_id' => $this->user_id,
            'admin_id' => $this->admin_id,
            'type' => $this->type,
            'message' => $this->message,
            'related_model' => $this->related_model,
            'related_model_id' => $this->related_model_id,
            'is_read' => $this->is_read,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'profile_picture' => $this->user->profile_picture,
                ];
            }),
'related_user' => $this->when(
    $this->related_model === \App\Models\User::class || $this->related_model === 'User',
    function () {
        return $this->related ? [
            'id' => $this->related->id,
            'name' => $this->related->name,
            'profile_picture' => $this->related->profile_picture,
        ] : null;
    }
),
        ];
    }
}
