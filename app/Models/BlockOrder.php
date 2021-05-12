<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class BlockOrders
 *
 * @property int $id
 * @property int $block_id
 * @property int $user_id Админ, который заказал выписку. Не обновляется.
 * @property string $type
 * @property int $status
 * @property int $transaction_id
 * @property int $document_id
 * @property string|null $path
 * @property Carbon|null $pay_date
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 *
 * @property-read Block $block
 * @property-read string|null $url
 *
 * @package App\Models
 * @mixin Builder
 */
class BlockOrder extends Model
{
    protected $table = 'block_orders';

    protected $fillable = [
        'id',
        'block_id',
        'user_id',
        'type',
        'status',
        'transaction_id',
        'document_id',
        'path',
        'pay_date',
    ];

    public const STATUS_NEW = 1;
    public const STATUS_PAY = 2;
    public const STATUS_DOWNLOAD = 3;
    public const STATUS_ERROR = 4;

    public const TYPE_XZP = 'XZP'; // Выписка из ЕГРН об объекте недвижимости
    public const TYPE_SOPP = 'SOPP'; // Выписка о переходе прав на объект недвижимости
    public const TYPE_SKS = 'SKS'; // Выписка из ЕГРН о кадастровой стоимости объекта недвижимости
    public const TYPE_KPT = 'KPT'; // Кадастровый план территории


    /**
     * @return HasOne
     */
    public function block(): HasOne
    {
        return $this->hasOne(Block::class, 'id', 'block_id');
    }

    /**
     * @return string|null
     */
    public function getUrlAttribute(): ?string
    {
        return 'https://tolko.hb.bizmrg.com' . $this->path;
    }
}
