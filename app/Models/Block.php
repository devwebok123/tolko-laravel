<?php

namespace App\Models;

use App\Models\Traits\NumberFormatter;
use App\Models\Traits\Selectable;
use App\Services\Models\BlockAdsService;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Block
 *
 * @property int $id Квартира
 * @property int $building_id Здание
 * @property int $floor Этаж
 * @property int $flat_number Номер квартиры
 * @property float $area Общая площадь
 * @property float $living_area Жилая площадь
 * @property float $kitchen_area Площадь кухни
 * @property int $type Номер
 * @property string $type_description Тип помещения описание
 * @property int $rooms Количество комнат
 * @property int $rooms_type Типы комнат
 * @property string $rooms_type_description Тип комнат описание
 * @property int $balcony Количество балконов
 * @property string $balcony_description Количество балконов описание
 * @property int $windowsInOut Куда выходят окна
 * @property string $windows_in_out_description Куда выходят окна описание
 * @property int $separate_wc_count Раздельных сан. узлов
 * @property int $combined_wc_count Смежных сан. узлов
 * @property int $renovation Ремонт
 * @property int $renovation_description Ремонт описание
 * @property array $filling В квартире
 * @property array $filling_descriptions В квартире описания
 * @property array $shower_bath В сан. узле
 * @property array $shower_bath_descriptions В сан. узле описания
 * @property array|string[] $living_conds Условия проживания
 * @property array $living_conds_descriptions Условия проживания описания
 * @property int $tenant_count_limit Макс. количество проживающих
 * @property string $cadastral_number Кадастровый номер
 * @property string $description Описание
 * @property string $cian_feed_description Описание
 * @property string $comment Комментарий
 * @property string $video_url Сссылка на видео
 * @property int $status Статус
 * @property string $status_description Статус описание
 * @property int $out_of_market Out Of Market
 * @property int $currency Валюта
 * @property string $currency_description Валюта
 * @property int $contract_signed Договор подписан
 * @property int $commission_type Тип комиссии
 * @property string $commission_type_description Тип комиссии описание
 * @property float $commission Комиссия
 * @property float $commission_amount Сумма коммиссии
 * @property string $commission_comment Комментарий для комиссии
 * @property-read int $client_commission_percent Процент коммиссии с клиента, если коммиссия с владельца то 0
 * @property array $included Included
 * @property array $included_descriptions Included описания
 * @property int $parking_cost Стоимость пракомета
 * @property float $cost Цена
 * @property float $deposit залог
 * @property-read  float $cost_meter Цена за 1 метр
 * @property-read null|string $cost_formatted
 * @property-read null|string $deposit_formatted
 * @property float $bargain Сделка
 * @property int $cian ЦИАН
 * @property string $ad_title Название для досок
 * @property int $bet Ставка
 * @property int|null $cian_offer_id
 * @property int|null $avito_promo
 * @property int|null $yandex_promo
 * @property int|null $ads_api_id
 * @property string|null $contact (AMO CRM сделка он же собственник)
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Building $building
 * @property-read BlockPhoto[]|Collection $photos
 * @property-read BlockPhoto[]|Collection $photosDraft
 * @property-read BlockPhoto[]|Collection $photosActive
 * @property-read BlockPhoto[]|Collection $simplePhotos
 * @property-read BlockPhoto[]|Collection $planPhotos
 * @property-read BlockPublicationStatistic[]|Collection $publicationsStatistic
 * @property-read  int $cian_publication_date_count
 * @property-read  string $avito_promo_description
 * @property-read  string $yandex_promo_description
 * @property-read BlockOrder|null $order
 *
 * @method static bool|null forceDelete()
 * @method static bool|null restore()
 * @method static QueryBuilder|Building onlyTrashed()
 *
 * @mixin Builder
 */
class Block extends Model
{
    use SoftDeletes, Selectable, HasFactory, NumberFormatter;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'building_id',
        'floor',
        'flat_number',
        'area',
        'living_area',
        'kitchen_area',
        'type',
        'rooms',
        'rooms_type',
        'balcony',
        'windowsInOut',
        'separate_wc_count',
        'combined_wc_count',
        'renovation',
        'filling',
        'shower_bath',
        'living_conds',
        'tenant_count_limit',
        'cadastral_number',
        'description',
        'comment',
        'video_url',
        'status',
        'out_of_market',
        'currency',
        'contract_signed',
        'commission_type',
        'commission',
        'commission_comment',
        'included',
        'parking_cost',
        'cost',
        'deposit',
        'bargain',
        'cian',
        'bet',
        'ad_title',
        'cian_offer_id',
        'avito_promo',
        'yandex_promo',
        'yandex_publication_date',
        'contact',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'building_id' => 'integer',
        'floor' => 'integer',
        'flat_number' => 'integer',
        'type' => 'integer',
        'rooms_type' => 'integer',
        'balcony' => 'integer',
        'windowsInOut' => 'integer',
        'separate_wc_count' => 'integer',
        'combined_wc_count' => 'integer',
        'renovation' => 'integer',
        'filling' => 'array',
        'shower_bath' => 'array',
        'living_conds' => 'array',
        'included' => 'array',
        'tenant_count_limit' => 'integer',
        'status' => 'integer',
        'out_of_market' => 'boolean',
        'currency' => 'integer',
        'contract_signed' => 'boolean',
        'commission' => 'decimal:2',
        'parking_cost' => 'decimal:2',
        'bargain' => 'decimal:2',
        'cian' => 'integer',
        'bet' => 'integer',
    ];

    const CUR_RUB = 1;
    const CUR_USD = 2;
    const CURS = [
        self::CUR_RUB => 'RUR',
        self::CUR_USD => 'USD',
    ];

    const CIAN_PROMO_PAID = 1;
    const CIAN_PROMO_HIGHLIGHTED = 2;
    const CIAN_PROMO_PREMIUM = 3;
    const CIAN_PROMO_TOP = 4;
    const CIAN_PROMOS = [
        self::CIAN_PROMO_PAID => 'paid',
        self::CIAN_PROMO_HIGHLIGHTED => 'highlight',
        self::CIAN_PROMO_PREMIUM => 'premium',
        self::CIAN_PROMO_TOP => 'top3',
    ];

    const ROOM_TYPE_SEPARATE = 1;
    const ROOM_TYPE_COMBINED = 2;
    const ROOM_TYPE_BOTH = 3;
    const ROOM_TYPES = [
        self::ROOM_TYPE_SEPARATE => 'separate',
        self::ROOM_TYPE_COMBINED => 'combined',
        self::ROOM_TYPE_BOTH => 'both',
    ];

    // type
    const TYPE_FLAT = 1;
    const TYPE_APARTMENT = 2;
    const TYPES = [
        self::TYPE_FLAT => 'flat',
        self::TYPE_APARTMENT => 'apartment',
    ];

    // rooms
    const ROOM_1 = 1;
    const ROOM_2 = 2;
    const ROOM_3 = 3;
    const ROOM_4 = 4;
    const ROOM_5 = 5;
    const ROOM_6_PLUS = 6;
    const ROOM_FREE_PLANNING = 7;
    const ROOM_STUDIO = 9;
    const ROOMS = [
        self::ROOM_1 => 'room_1',
        self::ROOM_2 => 'room_2',
        self::ROOM_3 => 'room_3',
        self::ROOM_4 => 'room_4',
        self::ROOM_5 => 'room_5',
        self::ROOM_6_PLUS => 'room_6_plus',
        self::ROOM_STUDIO => 'studio',
        self::ROOM_FREE_PLANNING => 'free_planning',
    ];

    // balcony
    const BALCONY_0 = 0;
    const BALCONY_1 = 1;
    const BALCONY_2 = 2;
    const BALCONY_3 = 3;
    const BALCONY_4 = 4;
    const BALCONY_5 = 5;

    const BALCONIES = [
        self::BALCONY_0 => 'balcony_0',
        self::BALCONY_1 => 'balcony_1',
        self::BALCONY_2 => 'balcony_2',
        self::BALCONY_3 => 'balcony_3',
        self::BALCONY_4 => 'balcony_4',
        self::BALCONY_5 => 'balcony_5',
    ];

    // windowsInOut
    const WINDOW_YARD = 1;
    const WINDOW_STREET = 2;
    const WINDOW_YARD_AND_STREET = 3;
    const WINDOWS = [
        self::WINDOW_YARD => 'yard',
        self::WINDOW_STREET => 'street',
        self::WINDOW_YARD_AND_STREET => 'yardAndStreet',
    ];

    // separate_wc_count
    const WC_COUNT_0 = 0;
    const WC_COUNT_1 = 1;
    const WC_COUNT_2 = 2;
    const WC_COUNT_3 = 3;
    const WC_COUNT_4 = 4;
    const WC_COUNT_5 = 5;
    const WC_COUNTS = [
        self::WC_COUNT_0 => 'wc_count_0',
        self::WC_COUNT_1 => 'wc_count_1',
        self::WC_COUNT_2 => 'wc_count_2',
        self::WC_COUNT_3 => 'wc_count_3',
        self::WC_COUNT_4 => 'wc_count_4',
        self::WC_COUNT_5 => 'wc_count_5',
    ];

    // renovation
    const RENOVATION_COSMETIC = 1;
    const RENOVATION_EURO = 2;
    const RENOVATION_DESIGN = 3;
    const RENOVATION_NO = 4;
    const RENOVATIONS = [
        self::RENOVATION_COSMETIC => 'cosmetic',
        self::RENOVATION_EURO => 'euro',
        self::RENOVATION_DESIGN => 'design',
        self::RENOVATION_NO => 'no',
    ];

    // filling
    const FILLING_INTERNET = 1;
    const FILLING_ROOM_FURNITURE = 2;
    const FILLING_PHONE = 3;
    const FILLING_KITCHEN_FURNITURE = 4;
    const FILLING_TV = 5;
    const FILLING_WASHING_MACHINE = 6;
    const FILLING_AIR_CONDITIONING = 7;
    const FILLING_DISHWASHER = 8;
    const FILLING_REFRIGERATOR = 9;
    const FILLINGS = [
        self::FILLING_ROOM_FURNITURE => 'HasFurniture',
        self::FILLING_KITCHEN_FURNITURE => 'HasKitchenFurniture',
        self::FILLING_REFRIGERATOR => 'HasFridge',
        self::FILLING_DISHWASHER => 'HasDishwasher',
        self::FILLING_WASHING_MACHINE => 'HasWasher',
        self::FILLING_INTERNET => 'HasInternet',
        self::FILLING_PHONE => 'HasPhone',
        self::FILLING_TV => 'HasTv',
        self::FILLING_AIR_CONDITIONING => 'HasConditioner',
    ];

    // shower_bath
    const BATHROOM = 1;
    const SHOWER_CABIN = 2;
    const SHOWER_BATHS = [
        self::BATHROOM => 'bathroom',
        self::SHOWER_CABIN => 'shower_cabin',
    ];

    // living_conds
    const LIVING_COND_ONLY_ONE = 1;
    const LIVING_COND_ONLY_FAMILY = 2;
    const LIVING_COND_ONLY_SLAVS = 3;
    const LIVING_COND_NO_ANIMALS = 4;
    const LIVING_COND_NO_CHILDREN = 5;
    const LIVING_CONDS = [
        self::LIVING_COND_ONLY_ONE => 'only_one',
        self::LIVING_COND_ONLY_FAMILY => 'only_family',
        self::LIVING_COND_ONLY_SLAVS => 'only_slavs',
        self::LIVING_COND_NO_ANIMALS => 'no_animals',
        self::LIVING_COND_NO_CHILDREN => 'no_children',
    ];

    // commission_type
    const COMMISSION_TYPE_CLIENT = 1;
    const COMMISSION_TYPE_OWNER = 2;
    const COMMISSION_TYPES = [
        self::COMMISSION_TYPE_CLIENT => 'client',
        self::COMMISSION_TYPE_OWNER => 'owner',
    ];

    const INCLUDED_UTILITY = 1;
    const INCLUDED_METERS = 2;
    const INCLUDED_PARKING = 3;
    const INCLUDES = [
        self::INCLUDED_UTILITY => 'utility',
        self::INCLUDED_METERS => 'meters',
        self::INCLUDED_PARKING => 'parking',
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 2;
    const STATUS_DRAFT = 3;

    const STATUSES = [
        self::STATUS_ACTIVE => 'active',
        self::STATUS_NOT_ACTIVE => 'not_active',
        self::STATUS_DRAFT => 'draft',
    ];

    const AVITO_PROMO_FREE = 1;
    const AVITO_PROMO_HIGHLIGHT = 2;
    const AVITO_PROMO_XL = 3;
    const AVITO_PROMO_X2_1 = 4;
    const AVITO_PROMO_X5_1 = 5;
    const AVITO_PROMO_X10_1 = 6;

    const AVITO_PROMOS = [
        self::AVITO_PROMO_FREE => 'Free',
        self::AVITO_PROMO_HIGHLIGHT => 'Highlight',
        self::AVITO_PROMO_XL => 'XL',
        self::AVITO_PROMO_X2_1 => 'x2_1',
        self::AVITO_PROMO_X5_1 => 'x5_1',
        self::AVITO_PROMO_X10_1 => 'x10_1',
    ];

    const YANDEX_PROMO_FREE = 1;
    const YANDEX_PROMO_PREMIUM = 2;
    const YANDEX_PROMO_RAISE = 3;
    const YANDEX_PROMO_PROMOTION = 4;

    const YANDEX_PROMOS = [
        self::YANDEX_PROMO_FREE => 'free',
        self::YANDEX_PROMO_PREMIUM => 'premium',
        self::YANDEX_PROMO_RAISE => 'raise',
        self::YANDEX_PROMO_PROMOTION => 'promotion',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * @return HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(BlockPhoto::class)->orderBy('rank');
    }

    /**
     * @return HasMany
     */
    public function simplePhotos(): HasMany
    {
        return $this->hasMany(BlockPhoto::class)
            ->orderBy('rank')
            ->where('tag_id', BlockPhoto::TAG_ID_PHOTO)
            ->active();
    }

    /**
     * @return HasMany
     */
    public function planPhotos(): HasMany
    {
        return $this->hasMany(BlockPhoto::class)
            ->orderBy('rank')
            ->where('tag_id', BlockPhoto::TAG_ID_PLAN)
            ->active();
    }

    public function photosActive()
    {
        return $this->photos()->active();
    }

    public function photosDraft()
    {
        return $this->photos()->draft();
    }

    public function publicationsStatistic()
    {
        return $this->hasMany(BlockPublicationStatistic::class, 'block_id', 'id');
    }

    /**
     * @param string
     * @return string
     */
    public function getUpdatedAtAttribute($value): string
    {
        return date('d.m.Y', strtotime($value));
    }

    /**
     * @param $value
     * @return float|null
     */
    public function getAreaAttribute($value): ?float
    {
        return $this->round($value);
    }

    /**
     * @param $value
     * @return float|null
     */
    public function getKitchenAreaAttribute($value): ?float
    {
        return $this->round($value);
    }

    /**
     * @param $value
     * @return float|null
     */
    public function getLivingAreaAttribute($value): ?float
    {
        return $this->round($value);
    }

    /**
     * @return string|null
     */
    public function getCostFormattedAttribute(): ?string
    {
        return $this->bigIntFormat($this->cost);
    }

    /**
     * @return string|null
     */
    public function getDepositFormattedAttribute(): ?string
    {
        return $this->bigIntFormat($this->deposit);
    }

    public function getCostMeterAttribute()
    {
        if (!$this->area) {
            return 0;
        }
        return round($this->cost / $this->area);
    }

    /**
     * @param string
     * @return string
     */
    public function getBuildingNameAttribute(): string
    {
        $modelBuilding = Building::find($this->building_id);
        if ($modelBuilding) {
            $result = $modelBuilding->address;
            if (!empty($modelBuilding->name)) {
                $result .= ' (' . $modelBuilding->name . ')';
            }
            return $result;
        } else {
            return '';
        }
    }

    /**
     * @param array
     * @return void
     */
    public function setFillingAttribute($value)
    {
        $this->attributes['filling'] = implode(',', (array)$value);
    }

    /**
     * @param array
     * @return void
     */
    public function setShowerBathAttribute($value)
    {
        $this->attributes['shower_bath'] = implode(',', (array)$value);
    }

    /**
     * @param array
     * @return void
     */
    public function setLivingCondsAttribute($value)
    {
        $this->attributes['living_conds'] = implode(',', (array)$value);
    }

    /**
     * @param array
     * @return void
     */
    public function setIncludedAttribute($value)
    {
        $this->attributes['included'] = implode(',', (array)$value);
    }

    /**
     * @param array
     * @return array
     */
    public function getFillingAttribute($value): array
    {
        if (!$value) {
            return [];
        }

        return explode(',', $value);
    }

    /**
     * @return array
     */
    public function getFillingDescriptionsAttribute(): array
    {
        return self::currentOptionsLang($this->filling, self::FILLINGS, 'cruds.block.fields.filling_options');
    }

    /**
     * @param array
     * @return array
     */
    public function getShowerBathAttribute($value): array
    {
        if (!$value) {
            return [];
        }

        return explode(',', $value);
    }

    /**
     * @return array
     */
    public function getShowerBathDescriptionsAttribute(): array
    {
        return self::currentOptionsLang(
            $this->shower_bath,
            self::SHOWER_BATHS,
            'cruds.block.fields.shower_bath_options'
        );
    }

    /**
     * @param array
     * @return array
     */
    public function getLivingCondsAttribute($value): array
    {
        if (!$value) {
            return [];
        }

        return explode(',', $value);
    }

    /**
     * @return array
     */
    public function getLivingCondsDescriptionsAttribute(): array
    {
        return self::currentOptionsLang(
            $this->living_conds,
            self::LIVING_CONDS,
            'cruds.block.fields.living_conds_options'
        );
    }

    /**
     * @param array
     * @return array
     */
    public function getIncludedAttribute($value): array
    {
        if (!$value) {
            return [];
        }

        return explode(',', $value);
    }

    /**
     * @return array
     */
    public function getIncludedDescriptionsAttribute(): array
    {
        return self::currentOptionsLang($this->included, Block::INCLUDES, 'cruds.block.fields.included_options');
    }

    public function getTypeDescriptionAttribute(): string
    {
        if (!$this->type) {
            return '';
        }
        return trans('cruds.block.fields.type_options.' . self::TYPES[$this->type]);
    }

    public function getRoomsTypeDescriptionAttribute(): string
    {
        if (!$this->rooms_type) {
            return '';
        }
        return trans('cruds.block.fields.rooms_type_options.' . self::ROOM_TYPES[$this->rooms_type]);
    }

    public function getBalconyDescriptionAttribute(): string
    {
        if (!$this->balcony) {
            return '';
        }
        return trans('cruds.block.fields.balcony_options.' . self::BALCONIES[$this->balcony]);
    }

    public function getWindowsInOutDescriptionAttribute(): string
    {
        if (!$this->windowsInOut) {
            return '';
        }
        return trans('cruds.block.fields.windowsInOut_options.' . self::WINDOWS[$this->windowsInOut]);
    }

    public function getRenovationDescriptionAttribute(): string
    {
        if (!$this->renovation) {
            return '';
        }
        return trans('cruds.block.fields.renovation_options.' . self::RENOVATIONS[$this->renovation]);
    }

    public function getCommissionTypeDescriptionAttribute(): string
    {
        return trans('cruds.block.fields.commission_type_options.' . self::COMMISSION_TYPES[$this->commission_type]);
    }

    public function getStatusDescriptionAttribute()
    {
        return trans('cruds.block.fields.status_options.' . self::STATUSES[$this->status]);
    }

    public function getCianDescriptionAttribute(): string
    {
        return self::CIAN_PROMOS[$this->cian];
    }

    public function getCurrencyDescriptionAttribute(): string
    {
        return self::CURS[$this->currency];
    }

    public function getCommissionAmountAttribute(): string
    {
        return $this->cost / 100 * $this->commission;
    }

    /**
     * @return int
     * Пока что это нужно в одной вьюшке, которая тянется по запросу.
     * Возможно в будущем переделаем на агрегацию шедулером раз в интервал
     */
    public function getCianPublicationDateCountAttribute(): int
    {
        return app(BlockAdsService::class)->calculateDaysCounter($this, BlockAdsService::TYPE_CIAN);
    }

    public function getAvitoPromoDescriptionAttribute(): string
    {
        if (!$this->avito_promo) {
            return '';
        }
        return trans('cruds.block.fields.avito_promos.' . self::AVITO_PROMOS[$this->avito_promo]);
    }

    public function getYandexPromoDescriptionAttribute(): string
    {
        if (!$this->yandex_promo) {
            return '';
        }
        return trans('cruds.block.fields.yandex_promos.' . self::YANDEX_PROMOS[$this->yandex_promo]);
    }

    /**
     * @return int
     */
    public function getAvitoPublicationDateCountAttribute(): int
    {
        return app(BlockAdsService::class)->calculateDaysCounter($this, BlockAdsService::TYPE_AVITO);
    }

    /**
     * @return int
     */
    public function getYandexPublicationDateCountAttribute(): int
    {
        return app(BlockAdsService::class)->calculateDaysCounter($this, BlockAdsService::TYPE_YANDEX);
    }

    /**
     * @return string
     */
    public function getCianFeedDescriptionAttribute(): string
    {
        return 'ID:' . $this->id . PHP_EOL . $this->description;
    }

    public function getClientCommissionPercentAttribute(): int
    {
        return $this->commission_type === self::COMMISSION_TYPE_CLIENT ? (int)$this->commission : 0;
    }

    /**
     * На время, пока выписка одна и не рисуем таблиц.
     * @return HasOne
     */
    public function order(): HasOne
    {
        return $this->hasOne(BlockOrder::class, 'block_id', 'id')
            ->whereType(BlockOrder::TYPE_XZP);
    }
}
