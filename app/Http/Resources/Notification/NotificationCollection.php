<?php


namespace App\Http\Resources\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class NotificationCollection extends ResourceCollection
{
    public static $wrap = 'items';

    public $collects = NotificationResource::class;
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
