<?php

namespace App\Http\Resources\Metro;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MetroCollection extends ResourceCollection
{
    public static $wrap = 'items';

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
