<?php


namespace App\Services\Models;


use App\Models\Block;
use App\Models\BlockPublicationHistory;
use App\Models\User;

class BlockPublicationHistoryService
{
    public function makeHistory(Block $block, User $user = null)
    {
        $type = $block->cian || $block->avito_promo || $block->yandex_promo ?
            BlockPublicationHistory::TYPE_ADD : BlockPublicationHistory::TYPE_REMOVE;

        return BlockPublicationHistory::create([
            'user_id' => $user ? $user->id : null,
            'block_id' => $block->id,
            'type' => $type,
            'cian_promo' => $block->cian,
            'bet' => $block->bet,
            'ad_title' => $block->ad_title,
            'avito_promo' => $block->avito_promo,
            'yandex_promo' => $block->yandex_promo,
        ]);
    }
}
