<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Building\AddressInfoRequest;
use App\Http\Requests\Building\AddressSuggestRequest;
use App\Http\Requests\Building\AutocompleteAddressRequest;
use App\Http\Requests\Building\MassDestroyRequest;
use App\Http\Requests\Building\StoreRequest;
use App\Http\Requests\Building\UpdateRequest;
use App\Http\Resources\Building\BuildingCollection;
use App\Http\Resources\Building\BuildingResource;
use App\Models\Building;
use App\Services\DadataService;
use App\Services\Models\BuildingService;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BuildingController extends Controller
{
    /**
     * @var DadataService
     */
    private $dadataService;

    /**
     * BuildingController constructor.
     *
     * @param DadataService $dadataService
     * @return void
     */
    public function __construct(DadataService $dadataService)
    {
        $this->dadataService = $dadataService;
    }

    /**
     * @param Request $request
     * @return BuildingCollection
     */
    public function index(Request $request)
    {
        $select = ['id', 'address', 'name', 'region_id', 'type', 'class'];
        /** @var Builder $buildings */
        $query = Building::select($select)->with('region')->withCount('blocks');
        if ($request->get('query')) {
            $query->where('address', 'like', '%' . $request->get('query') . '%');
        }

        $buildings = $query->paginate($request->get('per_page'), $select, 'pg', $request->get('page'));

        return new BuildingCollection($buildings);
    }

    /**
     * @param StoreRequest $request
     * @return BuildingResource
     */
    public function store(StoreRequest $request)
    {
        $building = Building::create($request->validated());

        return new BuildingResource($building);
    }

    /**
     * @param Request $request
     * @param Building $building
     * @return BuildingResource
     */
    public function show(Request $request, Building $building)
    {
        return new BuildingResource($building);
    }

    /**
     * @param UpdateRequest $request
     * @param Building $building
     * @return BuildingResource
     */
    public function update(UpdateRequest $request, Building $building)
    {
        $building->update($request->validated());

        return new BuildingResource($building);
    }

    /**
     * @param Request $request
     * @param Building $building
     * @return Response
     * @throws Exception
     */
    public function destroy(Request $request, Building $building)
    {
        $building->delete();

        return response()->noContent();
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->get('ids');
        /** @var Builder $buildings */
        $buildings = Building::whereIn('id', $ids);
        $buildings->delete();

        return response(null, 204);
    }

    /**
     * @param AutocompleteAddressRequest $request
     * @param BuildingService $service
     * @return BuildingCollection
     */
    public function getBuildingAddress(AutocompleteAddressRequest $request, BuildingService $service)
    {
        return new BuildingCollection($service->getAddressBySearch($request->getAddress(), $request->getSource()));
    }

    /**
     * @param Request $request
     * @return BuildingCollection
     */
    public function getBuildingName(Request $request)
    {
        // Search by buiding name
        $names = Building::query()
            ->select('id', 'name', 'address')
            ->where('name', 'LIKE', '%' . $request->input('buildings_name') . '%');

        // Search by address
        $names = $names->orWhere(function ($query) use ($request) {
            $words = explode(' ', $request->input('buildings_name'));
            foreach ($words as $word) {
                $word = trim($word);
                if (!empty($word)) {
                    $query->where('address', 'LIKE', '%' . $word . '%');
                }
            }
        });

        $names = $names
            ->limit(30)
            ->get();

        return new BuildingCollection($names);
    }

    public function addressSuggest(AddressSuggestRequest $request)
    {
        return $this->dadataService->getAddressSuggest($request->q);
    }

    public function addressInfo(AddressInfoRequest $request)
    {
        return $this->dadataService->addressInfo($request->q);
    }
}
