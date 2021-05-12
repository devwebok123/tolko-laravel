<?php


namespace App\Http\Resources\BlockOrder;

use Illuminate\Http\Resources\Json\JsonResource;

class BlockOrderResource extends JsonResource
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
            'block_id' => $this->block_id,
            'type' => $this->type,
            'status' => $this->status,
            'document_id' => $this->document_id,
            'transaction_id' => $this->transaction_id,
            'path' => $this->path,
            'url' => $this->url,
            'pay_date' => $this->pay_date ? $this->pay_date->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
