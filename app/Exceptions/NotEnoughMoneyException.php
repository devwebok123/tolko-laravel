<?php


namespace App\Exceptions;

use App\Models\BlockOrder;
use App\Services\Models\NotificationService;

class NotEnoughMoneyException extends \Exception
{
    /** @var BlockOrder $order */
    protected $order;

    public function __construct(BlockOrder $order, $message = "")
    {
        $this->order = $order;
        parent::__construct($message);
    }

    public function report()
    {
        app(NotificationService::class)->createNotEnoughMoneyNotification($this->getOrder()->block_id);

        return false;
    }

    /**
     * @return BlockOrder
     */
    public function getOrder(): BlockOrder
    {
        return $this->order;
    }
}
