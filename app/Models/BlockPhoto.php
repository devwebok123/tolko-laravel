<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BlockPhoto
 *
 * @property int $id Квартира
 * @property int $block_id Блок
 * @property int $tag_id Тэг
 * @property int $rank Сортировка
 * @property int $status Статус фотки: активная, не активная, черновик
 * @property string $name Название
 * @property-read Block $block
 * @property-read string $preview
 * @property-read string $tag_title
 *
 * @mixin Builder
 */
class BlockPhoto extends Model
{
    public const TAG_ID_PHOTO = 1;
    public const TAG_ID_PLAN = 2;
    public const TAGS = [
        self::TAG_ID_PHOTO => 'фото',
        self::TAG_ID_PLAN => 'планировка',
    ];

    public const STATUS_ACTIVE = 1;
    public const STATUS_NOT_ACTIVE = 2;
    public const STATUS_DRAFT = 3;

    public const STATUSES = [
        self::STATUS_ACTIVE => 'Активный',
        self::STATUS_NOT_ACTIVE => 'Не активный',
        self::STATUS_DRAFT => 'Черновик',
    ];

    /**
     * @var string
     */
    protected $table = 'block_photos';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'rank',
        'status',
        'tag_id',
        'image',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'tag_id' => self::TAG_ID_PHOTO,
    ];

    /**
     * @param bool $isPublic
     * @return string
     * @throw Exception
     */
    public function getPhotoName(bool $isPublic = false): string
    {
        $mode = $this->getMode($isPublic);

        return $mode . "/{$this->block_id}/{$this->id}.jpg";
    }

    /**
     * @param bool $isPublic
     * @return string
     */
    protected function getMode(bool $isPublic): string
    {
        if ($this->status === self::STATUS_DRAFT) {
            return 'draft';
        }

        return $isPublic ? 'public' : 'origin';
    }

    /**
     * @return string
     */
    public function getPreviewAttribute(): string
    {
        return 'https://tolko.hb.bizmrg.com/' . $this->getPhotoName(true);
    }

    /**
     * @return BelongsTo|Block
     */
    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => (string)$this->name,
            'rank' => (string)$this->rank,
            'tag_id' => (string)$this->tag_id,
            'tag_title' => (string)$this->tag_title,
            'status' => (int)$this->status,
            'preview' => $this->preview,
        ];
    }

    /**
     * @return string
     */
    public function getTagTitleAttribute(): ?string
    {
        return self::TAGS[$this->tag_id];
    }

    /**
     * Scope a query to only include active users.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include draft photos.
     *
     * @param $query
     * @return Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }
}
