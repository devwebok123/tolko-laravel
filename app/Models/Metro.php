<?php

namespace App\Models;

use App\Models\Traits\Selectable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MetroStation
 *
 * @property int $id
 * @property string $name
 * @property string $line
 * @property int $region_id
 * @property int $cian_id
 *
 * @property Region $region
 * @property Collection|Building[] $buildings
 *
 * @mixin Builder
 * @package App\Models
 */
class Metro extends Model
{
    use Selectable, HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'line',
        'region_id',
        'cian_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'line' => 'integer',
        'region_id' => 'integer',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
