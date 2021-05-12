<?php

namespace Database\Seeders;

use App\Exceptions\DadataException;
use App\Models\Building;
use App\Services\DadataService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws DadataException
     */
    public function run()
    {
        $json = File::get(storage_path('app/public/houses.json'));
        $data = json_decode($json);
        foreach ($data as $obj) {
            $info = DadataService::addressInfo($obj->address);
            $entity = array_filter([
                'address' => $obj->address,
                'region_id' => $info['region_id'] ?? 100,
                'floors' => (int)$obj->floors ?: null,
                // 'area' => $obj->square,
                'year_construction' => (int)$obj->year ?: null,
                'metro_id' => $info['metro_id'],
                'metro_id_2' => $info['metro_id_2'],
                'metro_id_3' => $info['metro_id_3'],
                'metro_distance' => $info['metro_distance'],
                'mkad_distance' => $info['mkad_distance'],
                'lat' => $info['lat'],
                'lng' => $info['lon'],
            ], function ($v) {
                return !empty($v);
            });
            Building::query()->insert($entity);
            if (!@$entity['floors']) {
                info('no Floors: '.$obj->address);
            }
            if (!$info['region_id']) {
                info('no Region: '.$obj->address);
            }
        }
    }
}
