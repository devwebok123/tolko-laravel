<?php

namespace App\Http\Resources\Block;

use App\Models\Block;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlockCollection extends ResourceCollection
{
    public static $wrap = 'items';

    public $collects = Block::class;
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
