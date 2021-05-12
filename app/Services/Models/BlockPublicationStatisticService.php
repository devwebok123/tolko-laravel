<?php


namespace App\Services\Models;


use App\Models\BlockPublicationStatistic;
use Carbon\Carbon;

class BlockPublicationStatisticService
{
    /**
     * @param int $source
     * @param int $blockId
     * @param Carbon $statDate
     * @param int $coverage
     * @param int $showsCount
     * @param int $searchesCount
     * @param int $phonesShows
     * @return BlockPublicationStatistic
     */
    public function createOrUpdate(
        int $source,
        int $blockId,
        Carbon $statDate,
        int $coverage,
        int $showsCount,
        int $searchesCount,
        int $phonesShows
    ): BlockPublicationStatistic
    {

        $statistic = BlockPublicationStatistic::whereStatDate($statDate->format('Y-m-d'))
            ->whereBlockId($blockId)
            ->whereSource($source)
            ->first();

        if (!$statistic) {
            $statistic = new BlockPublicationStatistic();
            $statistic->block_id = $blockId;
            $statistic->stat_date = $statDate->format('Y-m-d');
            $statistic->source = $source;
        }

        $statistic->coverage = $coverage;
        $statistic->shows_count = $showsCount;
        $statistic->searches_count = $searchesCount;
        $statistic->phones_shows = $phonesShows;

        $statistic->save();

        return $statistic;
    }

}
