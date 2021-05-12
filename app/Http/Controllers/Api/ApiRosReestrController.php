<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\EmptyCadastralException;
use App\Http\Resources\BlockOrder\BlockOrderResource;
use App\Models\Block;
use App\Http\Controllers\Controller;
use App\Models\BlockOrder;
use App\Services\Models\BlockOrderService;
use App\Services\RosReestr\OrderService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\ApiRosReestr\ObjectInfoFullRequest;
use App\Http\Requests\ApiRosReestr\CadastralRequest;

class ApiRosReestrController extends Controller
{
    /**
     * @var string
     */
    private $token = null;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->token = env('ROSREESTR_TOKEN');
    }

    /**
     * @param ObjectInfoFullRequest $objectInfoFullRequest
     */
    public function objectInfoFull(ObjectInfoFullRequest $objectInfoFullRequest)
    {
        // Vars
        $validated = $objectInfoFullRequest->validated();
        $query = $validated['query'];
        $endPoint = 'https://apirosreestr.ru/api/cadaster/objectInfoFull';

        // Request
        $response = Http::withHeaders(['TOKEN' => $this->token])
            ->post($endPoint, ['query' => $query]);
        $responseObject = $response->json();

        // Checking available
        $available = false;
        if (isset($responseObject['encoded_object'])) {
            if (isset($responseObject['documents']['XZP']['available'])) {
                $available = $responseObject['documents']['XZP']['available'];
            }
        }

        //
        return response()->json(['available' => $available]);
    }

    /**
     * @param Block $block
     * @return string
     */
    public function search(Block $block)
    {
        // Vars
        $endPoint = 'https://apirosreestr.ru/api/cadaster/search';

        // settlement spike
        $settlement = $block->building->address_region;
        if (!empty($block->building->address_settlement)) {
            $settlement = $block->building->address_settlement;
        }
        if (!empty($block->building->address_city)) {
            $settlement = $block->building->address_city;
        }

        // Region Spike
        $regionCode = \mb_substr($block->building->address_region_code, 0, 2, 'UTF-8');

        // Request
        $response = Http::withHeaders(['TOKEN' => $this->token])
            ->post($endPoint, [
                'query' => [
                    'region_code' => $regionCode,
                    'raion' => $block->building->address_region,
                    'settlement' => $settlement,
                    'street' => $block->building->address_street,
                    'house' => $block->building->address_house,
                    'building' => $block->building->address_building,
                    'block' => $block->building->address_block,
                    'flat' => $block->flat_number,
                ]
            ]);

        return response()->json($response->json());
    }

    /**
     * @param Block $block
     * @param CadastralRequest $cadastralRequest
     * @return string
     */
    public function cadastral(Block $block, CadastralRequest $cadastralRequest)
    {
        $block->cadastral_number = $cadastralRequest->getCadastral();
        $block->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Block $block
     * @param BlockOrderService $service
     * @return BlockOrderResource|JsonResponse
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function saveOrder(Block $block, BlockOrderService $service)
    {
        try {
            return response()->json(BlockOrderResource::make($service->saveOrder($block, BlockOrder::TYPE_XZP)));
        } catch (EmptyCadastralException $e) {
            return response()->json(['error' => 'Unprocessable', response()::HTTP_UNPROCESSABLE_ENTITY]);
        }
    }
}
