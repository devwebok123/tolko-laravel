<?php

namespace App\Models;

use App\Models\Traits\Selectable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Building
 *
 * @property int $id Здания
 * @property string $address Адрес
 * @property string $name Название
 * @property int $region_id Район
 * @property int $metro_id Метро
 * @property int $metro_id_2 Метро 2
 * @property int $metro_id_3 Метро 3
 * @property int $metro_distance Расстояние до метро (м.)
 * @property int $metro_time Время до метро (минут)
 * @property int $metro_time_type Пешком/транспортом
 * @property string $metro_time_type_description Пешком/транспортом перевод
 * @property int $mkad_distance Расстояние до МКАД
 * @property int|null $year_construction
 * @property string $type Класс
 * @property string $series Серия
 * @property string $ceil_height Высота потолков
 * @property string $passenger_lift_count Пассажирских лифтов
 * @property string $cargo_lift_count Грузовых лифтов
 * @property string $garbage_chute Мусоропровод
 * @property string $class Класс
 * @property int $floors Этажность
 * @property int $parking_type Паркинг
 * @property bool $near_infra Инфраструктура рядом
 * @property float $lat Широта
 * @property float $lng Долгота
 * @property string|null $ads_api_address Адс апи адресс
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read  Region $region
 * @property-read Collection|Block[] $blocks
 * @property-read Metro|null $metro
 *
 * @mixin Builder
 */
class Building extends Model
{
    use HasFactory;
    use Selectable;

    const TYPE_BRICK = 1;
    const TYPE_MONOLITH = 2;
    const TYPE_PANEL = 3;
    const TYPE_BLOCK = 4;
    const TYPE_WOOD = 5;
    const TYPE_MONOLITH_BRICK = 6;
    const TYPE_STALIN = 7;

    const TYPES = [
        self::TYPE_BRICK => 'brick',
        self::TYPE_MONOLITH => 'monolith',
        self::TYPE_PANEL => 'panel',
        self::TYPE_BLOCK => 'block',
        self::TYPE_WOOD => 'wood',
        self::TYPE_MONOLITH_BRICK => 'monolithBrick',
        self::TYPE_STALIN => 'stalin',
    ];

    const TIME_TYPE_FOOT = 1;
    const TIME_TYPE_TRANSPORT = 2;

    const TIME_TYPES = [
        self::TIME_TYPE_FOOT => 'walk',
        self::TIME_TYPE_TRANSPORT => 'transport',
    ];

    const CLASSES = [
        'A' => 'A',
        'B' => 'B',
        'C' => 'C',
        'D' => 'D',
    ];

    const PARKING_TYPE_LAND = 1;
    const PARKING_TYPE_MULTI = 2;
    const PARKING_TYPE_SUB = 3;
    const PARKING_TYPE_ROOF = 4;

    const PARKING_TYPES = [
        self::PARKING_TYPE_LAND => 'ground',
        self::PARKING_TYPE_MULTI => 'multilevel',
        self::PARKING_TYPE_SUB => 'underground',
        self::PARKING_TYPE_ROOF => 'roof',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
        'name',
        'region_id',
        'metro_id',
        'metro_id_2',
        'metro_id_3',
        'metro_time',
        'metro_time_type',
        'metro_distance',
        'mkad_distance',
        'year_construction',
        'type',
        'series',
        'ceil_height',
        'passenger_lift_count',
        'cargo_lift_count',
        'garbage_chute',
        'class',
        'floors',
        'parking_type',
        'near_infra',
        'lat',
        'lng',
        'address_region_code',
        'address_raion',
        'address_settlement',
        'address_street',
        'address_address',
        'address_house',
        'address_building',
        'address_block',
        'address_index',
        'address_address',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'region_id' => 'integer',
        'metro_id' => 'integer',
        'metro_id_2' => 'integer',
        'metro_id_3' => 'integer',
        'metro_time' => 'integer',
        'metro_time_type' => 'integer',
        'metro_distance' => 'integer',
        'mkad_distance' => 'integer',
        'year_construction' => 'integer',
        'type' => 'integer',
        'ceil_height' => 'decimal:2',
        'passenger_lift_count' => 'integer',
        'cargo_lift_count' => 'integer',
        'garbage_chute' => 'boolean',
        'floors' => 'integer',
        'parking_type' => 'integer',
        'near_infra' => 'boolean',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'address_region_code' => 'integer',
        'address_index' => 'integer',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function metro()
    {
        return $this->belongsTo(Metro::class, 'metro_id');
    }

    public function metro2()
    {
        return $this->belongsTo(Metro::class, 'metro_id_2');
    }

    public function metro3()
    {
        return $this->belongsTo(Metro::class, 'metro_id_3');
    }

    /**
     * @return string
     */
    public function getAddressNameAttribute(): string
    {
        $value = $this->address;

        if (!empty($this->name)) {
            $value .= ', ' . $this->name;
        }

        return $value;
    }

    /**
     * Get the comments for the blog post.
     */
    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public function getMetroTimeTypeDescriptionAttribute(): string
    {
        if (!$this->metro_time) {
            return '';
        }
        return trans('cruds.building.fields.metro_time_type_options.' . self::TIME_TYPES[$this->metro_time_type]);
    }
}
