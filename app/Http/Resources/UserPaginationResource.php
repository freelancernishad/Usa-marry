<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserPaginationResource extends ResourceCollection
{
    public $collects = UserResource::class;

    protected $authUser;

    public function withAuthUser($user)
    {
        $this->authUser = $user;
        return $this;
    }

    public function toArray($request)
    {
        // Inject match percentage if needed
        $this->collection->transform(function ($user) {
            $user->match_percentage = calculateMatchPercentageAllFields($this->authUser, $user);
            return $user;
        });

        return [
            'current_page' => $this->currentPage(),
            'data' => UserResource::collection($this->collection),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }
}
