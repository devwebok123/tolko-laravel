<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Cadastral\CadastralCheckRequest;
use App\Http\Controllers\Controller;
use App\Models\Block;
use Illuminate\Support\Facades\Http;

class CadastralController extends Controller
{
    /**
     * @param Block $block
     * @param CadastralCheckRequest $request
     * @return string
     */
    public function check(Block $block, CadastralCheckRequest $request)
    {
        //
        $data = $request->validated();

        if (!empty($data['cadastral_number'])) {
            // Get response
            $info = $this->getInfoByCadastral($data['cadastral_number']);

            if (isset($info['error'])) {
                $json = [];
                $json ['status'] = 'error';
                $json ['code'] = $info ['error']['code'];
                $json ['mess'] = $info ['error']['mess'];

                $block->load('building');

                $query = createQueryArray($block);
                // номер региона, обязательно
                $query ['region_code'] = $block->building->address_region_code;

                // название города или района
                $query ['raion'] =  $block->building->address_region;
                if (!empty($block->building->address_city)) {
                    $query ['raion'] =  $block->building->address_city;
                }

                // населенный пункт, без типа
                if (!empty($block->building->address_settlement)) {
                    $query ['settlement'] =  $block->building->address_settlement;
                }

                // улица, без указания типа
                if (!empty($block->building->address_street)) {
                    $query ['street'] =  $block->building->address_street;
                }

                /*
                  "house": "38",    // номер дома
                  "building": "",    // строение
                  "block": "",    // корпус
                  "flat": "19"    // квартира или помещение
                */


                $info = $this->getCadastralByAddress($query);
            }

            if (isset($info['documents']['XZP']['available'])) {
                $json = [];
                $json ['status'] = 'success';
                $json ['available'] = $info['documents']['XZP']['available'];
            }

            return response()->json($json);
        }
    }

    /**
     * @param array $query
     * @return string
     */
    public function getCadastralByAddress($query)
    {
        //
        $endPoint = 'https://apirosreestr.ru/api/cadaster/search';
        //
        $token = env('ROSREESTR_TOKEN');
        //
        $response = Http::withHeaders([
            'TOKEN' => $token,
        ])->post($endPoint, [
            'mode' => 'lite',
            'query' => $query,
        ]);

        return $response->json();
    }

    /**
     * @param string $cadastralNumber
     * @return string
     */
    public function getInfoByCadastral($cadastralNumber)
    {
        $endPoint = 'https://apirosreestr.ru/api/cadaster/objectInfoFull';
        $token = env('ROSREESTR_TOKEN');

        $response = Http::withHeaders([
            'TOKEN' => $token,
        ])->post($endPoint, [
            'query' => $cadastralNumber,
        ]);

        return $response->json();
    }
}
