<?php


namespace App\Services\Models;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// TODO maybe  migrate this to Repository classes
class NotificationService
{

    /**
     * @param int $source
     * @param int $externalId
     * @param int $type
     * @param string $text
     * @param Carbon $notificationDate
     * @param int|null $offerId
     * @param bool $isResolved
     * @return Notification
     */
    public function create(
        int $source,
        int $externalId,
        int $type,
        string $text,
        Carbon $notificationDate,
        int $offerId = null,
        bool $isResolved = false
    ): Notification {
        return Notification::create([
            'source' => $source,
            'external_id' => $externalId,
            'type' => $type,
            'text' => $text,
            'notification_date' => $notificationDate,
            'offer_id' => $offerId,
            'is_resolved' => $isResolved,
        ]);
    }

    /**
     * @param int $source
     * @param int $type
     * @param int $externalId
     * @param null|Carbon $date
     * @return Notification|null
     */
    public function getBySourceAndTypeAndExternalId(
        int $source,
        int $type,
        int $externalId,
        ?Carbon $date
    ): ?Notification {
        $query = Notification::whereSource($source)->whereType($type)->whereExternalId($externalId);

        if ($date) {
            $query->where('notification_date', 'like', $date->format('Y-m-d') . '%');
        }

        return $query->first();
    }

    /**
     * @param string $message
     * @param int $dataId
     * @return Notification
     */
    public function createTolkoNotificationWithoutDuplicate(string $message, int $dataId): Notification
    {
        $notification = Notification::where('type', Notification::TYPE_NOTIFICATION)
            ->where('text', $message)
            ->where('offer_id', $dataId)
            ->where('external_id', $dataId)
            ->where('source', Notification::SOURCE_TOLKO)
            ->where('day', Carbon::now())
            ->first();

        if ($notification) {
            return $notification;
        }

        return Notification::create([
            'type' => Notification::TYPE_NOTIFICATION,
            'day' => Carbon::now(),
            'text' => $message,
            'offer_id' => $dataId,
            'external_id' => $dataId,
            'source' => Notification::SOURCE_TOLKO,
            'notification_date' => Carbon::now(),
            'is_resolved' => false,
        ]);
    }

    /**
     * @param int $blockId
     * @return Notification
     */
    public function createNotificationAfterDownloadOrder(int $blockId): Notification
    {
        $notification = $this->getBySourceAndTypeAndExternalId(
            Notification::SOURCE_ORDERS,
            Notification::TYPE_NOTIFICATION,
            $blockId,
            Carbon::now()
        );
        if ($notification) {
            return $notification;
        }

        return $this->create(
            Notification::SOURCE_ORDERS,
            $blockId,
            Notification::TYPE_NOTIFICATION,
            'Выписка готова!',
            Carbon::now(),
            0
        );
    }

    /**
     * @param int $blockId
     * @return Notification
     */
    public function createNotEnoughMoneyNotification(int $blockId): Notification
    {
        $notification = $this->getBySourceAndTypeAndExternalId(
            Notification::SOURCE_ORDERS,
            Notification::TYPE_NOTIFICATION,
            $blockId,
            Carbon::now()
        );
        if ($notification) {
            return $notification;
        }
        return $this->create(
            Notification::SOURCE_ORDERS,
            $blockId,
            Notification::TYPE_NOTIFICATION,
            'На балансе не достаточно денег для оплаты выписки.',
            Carbon::now(),
            0
        );
    }
}
