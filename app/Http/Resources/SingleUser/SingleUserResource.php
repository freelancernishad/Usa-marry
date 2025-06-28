<?php

namespace App\Http\Resources\SingleUser;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleUserResource extends JsonResource
{
    public function toArray($request)
    {
        $authUser = auth()->user();

        // যদি আপনার calculateMatchPercentage() ফাংশন Controller এ থাকে, তাহলে call করবেন নিচের মত:
        $matchPercentage = calculateMatchPercentage($authUser, $this->resource);

        $matchDetails = getMatchDetails($authUser, $this->resource);


         $userDetails = new \App\Http\Resources\UserResource($authUser);


             return [
                'user' => $userDetails,
                'match_percentage' => $matchPercentage,
                'match_details' => $matchDetails,
            ];



    }
}
