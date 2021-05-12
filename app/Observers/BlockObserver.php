<?php


namespace App\Observers;

use App\Models\Block;
use App\Services\Models\BlockPublicationHistoryService;
use Illuminate\Support\Facades\Auth;

class BlockObserver
{
    /** @var BlockPublicationHistoryService $blockPublicationHistoryService */
    protected $blockPublicationHistoryService;

    public function __construct(BlockPublicationHistoryService $blockPublicationHistoryService)
    {
        $this->blockPublicationHistoryService = $blockPublicationHistoryService;
    }

    /**
     * @param Block $block
     */
    public function updated(Block $block): void
    {
        if ($block->isDirty('cian') ||
            $block->isDirty('bet') ||
            $block->isDirty('ad_title') ||
            $block->isDirty('avito_promo') ||
            $block->isDirty('yandex_promo')) {
            $this->blockPublicationHistoryService->makeHistory($block, Auth::user());
        }

        if ($block->isDirty('status')) {
            if ($block->status !== Block::STATUS_ACTIVE) {
                // TODO NEED FIX THIS NEW QUERY TO DATABASE
                Block::whereId($block->id)->update([
                    'cian' => null,
                    'bet' => null,
                    'cian_offer_id' => null,
                    'avito_promo' => null,
                    'yandex_promo' => 0,
                ]);
                $block->refresh();
                $this->blockPublicationHistoryService->makeHistory($block, Auth::user());
            }
        }
    }

    /**
     * @param Block $block
     */
    public function created(Block $block): void
    {
        if ($block->cian || $block->avito_promo || $block->yandex_promo) {
            $this->blockPublicationHistoryService->makeHistory($block, Auth::user());
        }
    }
}
