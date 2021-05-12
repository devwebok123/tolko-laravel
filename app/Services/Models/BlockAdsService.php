<?php


namespace App\Services\Models;

use App\Http\Requests\Block\MassMarketingRequest;
use App\Models\Block;
use App\Models\BlockPublicationHistory;
use Carbon\Carbon;

class BlockAdsService
{
    public const TYPE_CIAN = 'cian_promo';
    public const TYPE_AVITO = 'avito_promo';
    public const TYPE_YANDEX = 'yandex_promo';

    /**
     * @param MassMarketingRequest $request
     * @return int Rows updated by ids
     */
    public function updateAds(MassMarketingRequest $request): int
    {
        $blocks = Block::whereIn('id', $request->getIds())->get();

        /** @var Block $block */
        foreach ($blocks as $block) {
            if (!is_null($request->getBet())) {
                $block->bet = $request->getBet();
            }
            if (!is_null($request->getCian())) {
                $block->cian = $request->getCian() ?: null;
            }
            if (!is_null($request->getAvitoPromo())) {
                $block->avito_promo = $request->getAvitoPromo() ?: null;
            }
            if (!is_null($request->getYandexPromo())) {
                $block->yandex_promo = $request->getYandexPromo() ?: null;
            }
            $block->save();
        }

        return $blocks->count();
    }

    /**
     * @param Block $block
     * @param string $type
     * @return int
     */
    public function calculateDaysCounter(Block $block, string $type): int
    {
        $records = BlockPublicationHistory::query()->where('block_id', $block->id)
            ->orderBy('id', 'ASC')
            ->get();

        $days = 0;
        /** @var int|null $start */
        $start = null;
        /** @var int|null $stop */
        $stop = null;
        /** @var BlockPublicationHistory $record */
        foreach ($records as $record) {
            /* set start if not setted */
            if (!$start && $record->$type) {
                $start = $record->stat_date->getTimestamp();
            }
            /* set stop if start detected */
            if ($start && !$stop && !$record->$type) {
                $stop = $record->stat_date->getTimestamp();
            }
            /* if detect interval with start and stop calculate days */
            if ($start && $stop) {
                $days += ceil(($stop - $start) / 86400);
                $start = null;
                $stop = null;
            }
        }
        /* if start was detected and stop not detected, than calculate to now time */
        if ($start) {
            $days += ceil((Carbon::now()->getTimestamp() - $start) / 86400);
        }

        return $days;
    }
}
