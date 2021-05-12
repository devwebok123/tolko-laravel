<?php


namespace App\Models;


use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * Class BlockPublicationHistory
 * @property int $id
 * @property int $block_id
 * @property Carbon $stat_date
 * @property int $cian_promo
 * @property int $bet
 * @property string $add_title
 * @property int|null $avito_promo
 * @property int|null $yandex_promo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static bool|null forceDelete()
 * @method static bool|null restore()
 * @method static QueryBuilder|Building onlyTrashed()
 *
 * @mixin Builder
 */
class BlockPublicationHistory extends Model
{
    public const TYPE_ADD = 1;
    public const TYPE_REMOVE = 2;

    protected $table = 'block_publications_history';

    public $fillable = [
        'id',
        'user_id',
        'block_id',
        'type',
        'stat_date',
        'cian_promo',
        'bet',
        'ad_title',
        'avito_promo',
        'yandex_promo',
    ];

    protected $dates = [
        'stat_date'
    ];

}
