<?php


namespace App\Models;


use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * Class BlockPublicationStatistic
 * @property int $id
 * @property int $source
 * @property int $block_id
 * @property Carbon $stat_date
 * @property int coverage
 * @property int $shows_count
 * @property int $searches_count
 * @property int $phones_shows
 *
 * @method static bool|null forceDelete()
 * @method static bool|null restore()
 * @method static QueryBuilder|Building onlyTrashed()
 *
 * @mixin Builder
 */
class BlockPublicationStatistic extends Model
{
    public const SOURCE_CIAN = 1;

    protected $table = 'block_publications_statistic';

    public $fillable = [
        'id',
        'source',
        'block_id',
        'stat_date',
        'coverage',
        'shows_count',
        'searches_count',
        'phones_shows',
    ];

    protected $dates = [
        'stat_date'
    ];

}
