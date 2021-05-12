<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdmArea
 *
 * @property int $id
 * @property string $name
 *
 * @mixin Builder
 *
 * @method static Builder|AdmArea newModelQuery()
 * @method static Builder|AdmArea newQuery()
 * @method static Builder|AdmArea query()
 * @method static Builder|AdmArea whereId($value)
 * @method static Builder|AdmArea whereIsMoscow($value)
 * @method static Builder|AdmArea whereName($value)
 */
class AdmArea extends Model
{
    use HasFactory;

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
        'short_name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];
}
