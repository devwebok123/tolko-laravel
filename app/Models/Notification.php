<?php


namespace App\Models;

use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class Notification
 * @property int $id
 * @property Carbon $day
 * @property int $source
 * @property int $external_id
 * @property int $type
 * @property bool $is_resolved
 * @property string $text
 * @property int|null $offer_id
 * @property Carbon $notification_date
 *
 * @property Block|null $block
 *
 * @method static bool|null forceDelete()
 * @method static bool|null restore()
 * @method static QueryBuilder|Building onlyTrashed()
 *
 * @mixin Builder
 */
class Notification extends Model
{
    public const SOURCE_CIAN = 1;
    public const SOURCE_TOLKO = 2;
    public const SOURCE_ORDERS = 3;

    public const TYPE_NOTIFICATION = 1;
    public const TYPE_COMPLAINT = 2;

    protected $table = 'notifications';

    public $fillable = [
        'id',
        'day',
        'source',
        'external_id',
        'type',
        'is_resolved',
        'text',
        'offer_id',
        'notification_date',
    ];

    protected $dates = [
        'notification_date'
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
    ];

    /**
     * @return HasOne
     * Не использовать with для этой сущности...
     * В планах поправить это.
     */
    public function block(): HasOne
    {
        if ($this->source === self::SOURCE_ORDERS) {
            return $this->hasOne(Block::class, 'id', 'external_id');
        }

        return $this->hasOne(Block::class, 'cian_offer_id', 'offer_id');
    }
}
