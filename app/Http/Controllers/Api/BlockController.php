<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Block\BlockMassDestroyRequest;
use App\Http\Requests\Block\BlockSearchRequest;
use App\Http\Requests\Block\BlockStoreRequest;
use App\Http\Requests\Block\BlockUpdateRequest;
use App\Http\Requests\Block\MassMarketingRequest;
use App\Http\Resources\Block\BlockCollection;
use App\Http\Resources\Block\BlockResource;
use App\Models\Block;
use App\Models\BlockPublicationStatistic;
use App\Models\Building;
use App\Services\Models\BlockAdsService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BlockController extends Controller
{

    /**
     * @param BlockSearchRequest $modelFilter
     * @return BlockCollection
     */
    public function index(BlockSearchRequest $modelFilter)
    {
        return $modelFilter->paginate();
    }

    /**
     * @param BlockStoreRequest $request
     *
     * @return BlockResource
     */
    public function store(BlockStoreRequest $request)
    {
        $block = Block::query()->create($request->validated());

        return new BlockResource($block);
    }

    /**
     * @param Request $request
     * @param Block $block
     *
     * @return BlockResource
     */
    public function show(Request $request, Block $block)
    {
        return new BlockResource($block);
    }

    /**
     * @param BlockUpdateRequest $request
     * @param Block $block
     *
     * @return BlockResource
     */
    public function update(BlockUpdateRequest $request, Block $block)
    {
        $block->update($request->validated());

        return new BlockResource($block);
    }

    /**
     * @param Request $request
     * @param Block $block
     *
     * @return Response
     * @throws Exception
     */
    public function destroy(Request $request, Block $block)
    {
        $block->delete();

        return response()->noContent();
    }

    /**
     * Bulk deleting blocks
     *
     * @param BlockMassDestroyRequest $request
     * @return Response
     */
    public function massDestroy(BlockMassDestroyRequest $request)
    {
        $ids = $request->get('ids');
        Block::query()->whereIn('id', $ids)->delete();

        return response(null, 204);
    }

    /**
     * @param Request $request
     *
     * @return BlockCollection
     */
    public function getBlockIds(Request $request)
    {
        $blocks = Block::query()
            ->select('id')
            ->where('id', 'LIKE', '%' . $request->input('id') . '%')
            ->orderBy('id', 'asc')
            ->limit(100)
            ->get();

        return new BlockCollection($blocks);
    }

    /**
     * @param Request $request
     *
     * @return BlockCollection|array
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        // Search by ID
        if (is_numeric($query)) {
            $blocks = Block::query()
                ->select('id')
                ->where('id', 'LIKE', '%' . $query . '%')
                ->orderBy('id', 'asc')
                ->limit(100)
                ->get();

            return new BlockCollection($blocks);
        } else {
            // Search by buiding name
            $names = Building::query()
                ->select('buildings.id as building_id', 'buildings.name', 'buildings.address')
                ->where('buildings.name', 'LIKE', '%' . $request->input('query') . '%');
            // Search by address
            $names = $names->orWhere(function ($query) use ($request) {
                $words = explode(' ', $request->input('query'));
                foreach ($words as $word) {
                    $word = trim($word);
                    if (!empty($word)) {
                        $query->where('buildings.address', 'LIKE', '%' . $word . '%');
                    }
                }
            });
            $names = $names
                ->rightJoin('blocks', 'buildings.id', '=', 'blocks.building_id')
                ->where('blocks.status', '!=', Block::STATUS_DRAFT)
                ->limit(30)
                ->get();

            return ['items' => $names->toArray()];
        }
    }

    /**
     * @param MassMarketingRequest $request
     *
     * @return void
     */
    public function mass(MassMarketingRequest $request, BlockAdsService $service)
    {
        $service->updateAds($request);
    }

    /**
     * @param Block $block
     * @return View
     * @throws Exception
     */
    public function addtRowInfo(Block $block)
    {
        $block->load('simplePhotos', 'planPhotos');

        $statistic = BlockPublicationStatistic::query()
            ->selectRaw('sum(coverage) as coverage')
            ->addSelect(DB::raw('sum(shows_count) as shows_count'))
            ->addSelect(DB::raw('sum(searches_count) as searches_count'))
            ->addSelect(DB::raw('sum(phones_shows) as phones_shows'))
            ->where('block_id', $block->id)
            ->first();

        return view('block.filter_add_row_info', compact('block', 'statistic'));
    }

    /**
     * @param Block $block
     * @return void
     * @throws Exception
     */
    public function deactivate(Block $block)
    {
        $block->update(['status' => Block::STATUS_NOT_ACTIVE]);
    }

    /**
     * @param Block $block
     * @return void
     * @throws Exception
     */
    public function activate(Block $block)
    {
        $block->update(['status' => Block::STATUS_ACTIVE]);
    }

    /**
     * @param Block $block
     */
    public function draft(Block $block)
    {
        $block->update(['status' => Block::STATUS_DRAFT]);
    }
}
