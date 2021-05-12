<?php


namespace App\DataObjects\AdsApi\Announcements;

use App\DataObjects\BaseObject;

class Params extends BaseObject
{
    protected const TYPE_RENT = 'Сдам';

    protected const IS_COMMISSION_YES = 'Есть';
    protected const IS_COMMISSION_NO = 'Нет';

    protected const BUILDING_TYPE_PANEL = 'Панельный';

    protected const RENT_TIME = 'На длительный срок';

    protected const ROOM_COUNT_STUDIO = 'Студия';

    /**
     * Площадь кухни
     * @var float $kitchenArea
     */
    protected $kitchenArea;
    /**
     * Тип объявления
     * @var string $type
     */
    protected $type;
    /**
     * Этаж
     * @var int $floor
     */
    protected $floor;
    /**
     * Жилая площадь
     * @var float $livingArea
     */
    protected $livingArea;
    /**
     * Есть ли комиссия
     * @var bool $isCommission
     */
    protected $isCommission;
    /**
     * Размер комиссии
     * @var int $commission
     */
    protected $commission;
    /**
     * Тип дома
     * @var string $buildingType
     */
    protected $buildingType;
    /**
     * Количество комнат
     * @var int $roomsCount
     */
    protected $roomsCount;
    /**
     * Этажей в доме
     * @var int $floorsCount
     */
    protected $floorsCount;
    /**
     * Срок аренды
     * @var string $rentTime
     */
    protected $rentTime;
    /**
     * Площадь
     * @var float $area
     */
    protected $area;

    protected function __construct(array $data)
    {
        parent::__construct($data);
        $this->kitchenArea = $data['Площадь кухни'] ?? 0;
        $this->type = $data['Тип объявления'];
        $this->floor = $data['Этаж'];
        $this->livingArea = $data['Жилая площадь'] ?? 0;
        $this->isCommission = $data['Комиссия'] === self::IS_COMMISSION_YES;
        $this->buildingType = $data['Тип дома'] ?? '';
        $this->roomsCount = $data['Количество комнат'] === self::ROOM_COUNT_STUDIO ? 1 : $data['Количество комнат'];
        $this->floorsCount = $data['Этажей в доме'];
        $this->rentTime = $data['Срок аренды'];
        $this->commission = $data['Размер комиссии'] ?? 0;
        $this->area = $data['Площадь'];
    }

    /**
     * @return float
     */
    public function getKitchenArea(): float
    {
        return $this->kitchenArea;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getFloor(): int
    {
        return $this->floor;
    }

    /**
     * @return float
     */
    public function getLivingArea(): float
    {
        return $this->livingArea;
    }

    /**
     * @return bool
     */
    public function isCommission(): bool
    {
        return $this->isCommission;
    }

    /**
     * @return string
     */
    public function getBuildingType(): string
    {
        return $this->buildingType;
    }

    /**
     * @return int
     */
    public function getRoomsCount(): int
    {
        return $this->roomsCount;
    }

    /**
     * @return int
     */
    public function getFloorsCount(): int
    {
        return $this->floorsCount;
    }

    /**
     * @return string
     */
    public function getRentTime(): string
    {
        return $this->rentTime;
    }

    /**
     * @return int
     */
    public function getCommission(): int
    {
        return $this->commission;
    }

    /**
     * @return float
     */
    public function getArea(): float
    {
        return $this->area;
    }
}
