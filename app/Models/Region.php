<?php

namespace App\Models;

use App\Models\Traits\Selectable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class Region
 *
 * @property int $id
 * @property string $name
 * @property int $adm_area_id
 *
 * @property-read AdmArea $admArea
 *
 * @mixin Builder
 * @package App
 */
class Region extends Model
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
        'adm_area_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'adm_area_id' => 'integer',
    ];


    public function admArea()
    {
        return $this->belongsTo(AdmArea::class);
    }
}
