<?php


namespace App\DataObjects\Cian;

use App\DataObjects\BaseObject;

class NotificationListObject extends BaseObject
{
    /** @var int $totalCount */
    protected $totalCount;
    /** @var NotificationObject[] $notifications */
    protected $notifications;

    protected function __construct(array $data)
    {
        parent::__construct($data);

        $this->totalCount = $data['totalCount'];
        foreach ($data['items'] as $item) {
            $this->notifications[] = NotificationObject::createFromArray($item);
        }
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return NotificationObject[]
     */
    public function getNotifications(): array
    {
        return $this->notifications;
    }
}
