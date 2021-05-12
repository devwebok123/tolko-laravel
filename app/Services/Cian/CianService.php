<?php

namespace App\Services\Cian;

use App\Clients\CianClient;
use App\Models\Block;
use App\Models\BlockPublicationStatistic;
use App\Models\Notification;
use App\Services\Models\BlockPublicationStatisticService;
use App\Services\Models\NotificationService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class CianService
{
    /** @var CianClient $client */
    protected $client;
    /** @var BlockPublicationStatisticService $blockPublicationStatisticService */
    protected $blockPublicationStatisticService;
    /** @var NotificationService $notificationService */
    protected $notificationService;

    public function __construct(
        CianClient $client,
        BlockPublicationStatisticService $service,
        NotificationService $notificationService
    ) {
        $this->client = $client;
        $this->blockPublicationStatisticService = $service;
        $this->notificationService = $notificationService;
    }

    /**
     * @param Carbon $day
     *
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function importBlocks(Carbon $day): void
    {
        $orders = $this->client->getOrders();

        foreach ($orders as $key => $order) {
            if (!$order->getOfferId() && ($order->getErrors() || $order->getWarnings())) {
                //не опубликовано обьявление
                continue;
            }
            if ($key % 5 === 0) {
                echo 'SLEEP 1' . PHP_EOL;
                sleep(1);
            }
            $block = Block::find($order->getBlockId());

            if (!$block) {
                continue;
            }
            $block->cian_offer_id = $order->getOfferId();
            $block->save();

            try {
                $coverageStatistic = $this->client->getCoverageStatistic($order->getOfferId(), $day, $day);
            } catch (RequestException $e) {
                report($e);
                echo 'EXCEPTION IN GET COVERAGE: ' . $e->getMessage() . ', CODE: ' . $e->getCode() . PHP_EOL;
                continue;
            }
            try {
                $statisticByDates = $this->client->getViewsStatistic($order->getOfferId(), $day, $day);
            } catch (RequestException $e) {
                report($e);
                echo 'EXCEPTION IN GET VIEWS STATISTIC: ' . $e->getMessage() . ', CODE: ' . $e->getCode() . PHP_EOL;
                continue;
            }

            $this->blockPublicationStatisticService->createOrUpdate(
                BlockPublicationStatistic::SOURCE_CIAN,
                $order->getBlockId(),
                $day,
                $coverageStatistic->getCoverage(),
                $coverageStatistic->getShowsCount(),
                $coverageStatistic->getSearchesCount(),
                $statisticByDates->getPhoneShowByDays() ? $statisticByDates->getPhoneShowByDays()[0]->getShows() : 0
            );
        }
    }

    /**
     * @param int $page
     * @param int $limit
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function importComplaints(int $page = 1, int $limit = 10): void
    {
        $complaintsList = $this->client->getComplaints($page, $limit);
        foreach ($complaintsList->getComplaints() as $complaint) {
            $notification = $this->notificationService->getBySourceAndTypeAndExternalId(
                Notification::SOURCE_CIAN,
                Notification::TYPE_COMPLAINT,
                $complaint->getId()
            );
            if ($notification) {
                continue;
            }
            $this->notificationService->create(
                Notification::SOURCE_CIAN,
                $complaint->getId(),
                Notification::TYPE_COMPLAINT,
                $complaint->getText(),
                $complaint->getCreationDate(),
                $complaint->getOfferId()
            );
        }
        if ($page * $limit < $complaintsList->getTotalCount()) {
            $this->importComplaints(++$page, $limit);
        }
    }

    /**
     * @param int $page
     * @param int $limit
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function importNotifications(int $page = 1, int $limit = 10): void
    {
        $notificationsList = $this->client->getNotifications($page, $limit);
        foreach ($notificationsList->getNotifications() as $cianNotification) {
            $notification = $this->notificationService->getBySourceAndTypeAndExternalId(
                Notification::SOURCE_CIAN,
                Notification::TYPE_NOTIFICATION,
                $cianNotification->getId()
            );
            if ($notification) {
                continue;
            }
            $this->notificationService->create(
                Notification::SOURCE_CIAN,
                $cianNotification->getId(),
                Notification::TYPE_NOTIFICATION,
                $cianNotification->getText(),
                $cianNotification->getDate(),
            );
        }
        if ($page * $limit < $notificationsList->getTotalCount()) {
            sleep(1);//limited requests to cian api
            $this->importNotifications(++$page, $limit);
        }
    }
}
