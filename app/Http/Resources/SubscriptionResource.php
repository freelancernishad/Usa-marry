<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'plan' => optional($this->plan)->name,
            'features' => $this->plan_features ?? optional($this->plan)->features,
            'formatted_plan_features' => $this->formatted_plan_features ?? optional($this->plan)->formatted_features,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ];
    }
}
