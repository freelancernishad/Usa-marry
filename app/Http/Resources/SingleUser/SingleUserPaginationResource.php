<?php

namespace App\Http\Resources\SingleUser;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SingleUserPaginationResource extends ResourceCollection
{
    public $collects = SingleUserResource::class;

    public function toArray($request)
    {

     
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->collection,
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
            'to' => $this->lastItem(),
            'total' => $this->total(),

        ];
    }
}
