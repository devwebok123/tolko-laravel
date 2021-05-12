<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Claim
 * @property int $id
 * @property int $phone
 * @property string $referer
 * @property int $status
 * @property Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @mixin Builder
 * @package App\Models
 */
class Claim extends Model
{
    protected $table = 'claims';

    protected $fillable = [
        'phone',
        'referer',
        'status'
    ];

    protected $attributes = [
        'status' => self::STATUS_NEW,
    ];

    public const STATUS_NEW = 1;
}
