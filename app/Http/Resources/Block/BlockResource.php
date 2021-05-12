<?php

namespace App\Http\Resources\Block;

use Illuminate\Http\Resources\Json\JsonResource;

class BlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'building_id' => $this->building_id,
            'floor' => $this->floor,
            'flat_number' => $this->flat_number,
            'area' => $this->area,
            'living_area' => $this->living_area,
            'kitchen_area' => $this->kitchen_area,
            'type' => $this->type,
            'rooms' => $this->rooms,
            'balcony' => $this->balcony,
            'windowsInOut' => $this->windowsInOut,
            'separate_wc_count' => $this->separate_wc_count,
            'combined_wc_count' => $this->combined_wc_count,
            'renovation' => $this->renovation,
            'filling' => $this->filling,
            'shower_bath' => $this->shower_bath,
            'living_conds' => $this->living_conds,
            'tenant_count_limit' => $this->tenant_count_limit,
            'cadastral_number' => $this->cadastral_number,
            'description' => $this->description,
            'comment' => $this->comment,
            'video_url' => $this->video_url,
            'status' => $this->status,
            'out_of_market' => $this->out_of_market,
            'currency' => $this->currency,
            'contract_signed' => $this->contract_signed,
            'commission_type' => $this->commission_type,
            'commission' => $this->commission,
            'commission_comment' => $this->commission_comment,
            'included' => $this->included,
            'parking_cost' => $this->parking_cost,
            'cost' => (int)$this->cost,
            'bargain' => $this->bargain,
            'cian' => $this->cian,
        ];
    }
}
