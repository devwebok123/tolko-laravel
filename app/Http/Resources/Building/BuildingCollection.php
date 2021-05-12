<?php

namespace App\Http\Resources\Building;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class BuildingCollection extends ResourceCollection
{
    public static $wrap = 'items';

    public $collects = BuildingResource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return Collection
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
