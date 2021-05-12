<?php

namespace App\Http\Resources\Building;

use App\Http\Resources\Region\RegionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingResource extends JsonResource
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
            'address' => $this->address,
            'name' => $this->name,
            'type' => $this->type,
            'region_id' => $this->region_id,
            'region_name' => $this->region ? $this->region->name : null,
            'blocks_count' => $this->blocks_count,
            // 'metro_id' => $this->metro_id,
            // 'metro_id_2' => $this->metro_id_2,
            // 'metro_id_3' => $this->metro_id_3,
            // 'metro_time' => $this->metro_time,
            // 'metro_time_type' => $this->metro_time_type,
            // 'metro_distance' => $this->metro_distance,
            // 'mkad_distance' => $this->mkad_distance,
            // 'year_construction' => $this->year_construction,
            // 'type' => $this->type,
            // 'series' => $this->series,
            // 'ceil_height' => $this->ceil_height,
            // 'passenger_lift_count' => $this->passenger_lift_count,
            // 'cargo_lift_count' => $this->cargo_lift_count,
            // 'garbage_chute' => $this->garbage_chute,
            'class' => $this->class,
            // 'floors' => $this->floors,
            // 'parking_type' => $this->parking_type,
            // 'near_infra' => $this->near_infra,
            // 'lat' => $this->lat,
            // 'lng' => $this->lng,
        ];
    }
}
