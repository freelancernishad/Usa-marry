<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompactUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? '',
            'profile_picture' => $this->profile_picture ?? '',
            'age' => $this->age ?? '',
            'country' => optional($this->profile)->country,
            'state' => optional($this->profile)->state,
            'city' => optional($this->profile)->city,
            'marital_status' => $this->marital_status,
            'height' => $this->height ?? '',
            'caste' => $this->caste ?? '',
            'religion' => $this->religion ?? '',
            'highest_degree' => optional($this->profile)->highest_degree ?? '',
            'occupation' => optional($this->profile)->occupation ?? '',
            'plan_name' => $this->plan_name,
        ];
    }
}

