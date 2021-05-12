<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'source' => $this->source,
            'type' => $this->type,
            'is_resolved' => $this->is_resolved,
            'text' => $this->text,
            'offer_id' => $this->offer_id,
            'block_id' => $this->block ? $this->block->id : null,
            'notification_date' => $this->notification_date->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
