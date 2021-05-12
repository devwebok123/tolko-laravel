<?php

namespace App\Http\Controllers;

use App\DataObjects\Block\BlockIndexViewOptions;
use App\Http\Requests\Block\BlockSearchRequest;
use App\Http\Requests\Block\BlockStoreRequest;
use App\Http\Requests\Block\BlockUpdateRequest;
use App\Models\Block;
use App\Services\Models\BlockService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlockController extends Controller
{
    /**
     * @param BlockSearchRequest $modelFilter
     * @param BlockService $blockService
     * @return Response
     */
    public function index(BlockSearchRequest $modelFilter, BlockService $blockService)
    {
        // $searchAddress
        $searchAddress = null;
        if (request()->has('address')) {
            $searchAddress = request()->input('address');
        }

        $form = $blockService->buildSearchForm($modelFilter);
        $options = BlockIndexViewOptions::getInstance(
            true,
            true,
            false,
            true
        );

        return view('block.index', compact(
            'form',
            'searchAddress',
            'options'
        ));
    }

    /**
     * @return Response
     */
    public function create()
    {
        return view('block.create');
    }

    /**
     * @param BlockStoreRequest $request
     * @return Response
     */
    public function store(BlockStoreRequest $request)
    {
        $v = $request->validated();
        $block = Block::create($v);

        $request->session()->flash('block.id', $block->id);
        $request->session()->flash('block.operation_status', self::STATUS_CREATED);

        return redirect()->route('block.show', ['block' => $block->id]);
    }

    /**
     * @param Request $request
     * @param Block $block
     * @return Response
     */
    public function show(Request $request, Block $block)
    {
        return view('block.show', compact('block'));
    }

    /**
     * @param Request $request
     * @param Block $block
     * @return Response
     */
    public function edit(Request $request, Block $block)
    {
        return view('block.edit', compact('block'));
    }

    /**
     * @param BlockUpdateRequest $request
     * @param Block $block
     * @return Response
     */
    public function update(BlockUpdateRequest $request, Block $block)
    {
        $block->update($request->validated());

        $request->session()->flash('block.id', $block->id);
        $request->session()->flash('block.operation_status', self::STATUS_UPDATED);

        return redirect()->route('block.show', ['block' => $block->id]);
    }

    /**
     * @param Request $request
     * @param Block $block
     * @return Response
     */
    public function destroy(Request $request, Block $block)
    {
        $block->delete();

        $request->session()->flash('block.operation_status', self::STATUS_DELETED);

        return redirect()->route('block.index');
    }

    /**
     * @return Response
     */
    public function drafts()
    {

        $searchAddress = null;
        $form = null;
        $options = BlockIndexViewOptions::getInstance(false, false, true, false);

        return view('block.index', compact(
            'form',
            'searchAddress',
            'options'
        ));
    }
}
