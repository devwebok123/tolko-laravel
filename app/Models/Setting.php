<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $value
 *
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin Builder
 * @package App\Models
 */
class Setting extends Model
{
    public const NAME_PHONE_CIAN = 'phone_cian';
    public const NAME_PHONE_AVITO = 'phone_avito';
    public const NAME_PHONE_YANDEX = 'phone_yandex';

    public const AVAILABLE_SETTINGS = [
        self::NAME_PHONE_CIAN,
        self::NAME_PHONE_AVITO,
        self::NAME_PHONE_YANDEX,
    ];

    protected $fillable = [
        'id',
        'name',
        'value',
    ];
}
