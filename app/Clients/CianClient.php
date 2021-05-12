<?php


namespace App\Clients;

use App\DataObjects\Cian\ComplaintListObject;
use App\DataObjects\Cian\NotificationListObject;
use App\DataObjects\Cian\OrderOfferObject;
use App\DataObjects\Cian\CoverageStatisticObject;
use App\DataObjects\Cian\ViewsByDatesObject;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CianClient extends Client
{

    protected const BASE_URL = 'https://public-api.cian.ru/v1/';

    protected const URI_ORDER = 'get-order';
    protected const URI_SEARCH_COVERAGE = 'get-search-coverage';
    protected const URI_VIEWS_BY_DATES = 'get-views-statistics-by-days';
    protected const URI_COMPLAINTS = 'get-complaints';
    protected const URI_NOTIFICATIONS = 'get-notifications';

    /**
     * @return OrderOfferObject[]
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getOrders(): array
    {
        $response = $this->get(self::BASE_URL . self::URI_ORDER, $this->getHeaders());
        $orders = [];

        $content = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $data = $content['result']['offers'];

        foreach ($data as $value) {
            $orders[] = OrderOfferObject::createFromArray($value);
        }

        return $orders;
    }

    /**
     * @param int $offerId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return CoverageStatisticObject
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getCoverageStatistic(int $offerId, Carbon $startDate, Carbon $endDate): CoverageStatisticObject
    {
        $query = [
            'offerId' => $offerId,
            'dateFrom' => $startDate->format('Y-m-d'),
            'dateTo' => $endDate->format('Y-m-d'),
        ];
        $url = self::BASE_URL . self::URI_SEARCH_COVERAGE . '?' . http_build_query($query);

        $response = $this->get($url, $this->getHeaders());
        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return CoverageStatisticObject::createFromArray($data['result']);
    }

    /**
     * @param int $offerId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return ViewsByDatesObject
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getViewsStatistic(int $offerId, Carbon $startDate, Carbon $endDate): ViewsByDatesObject
    {
        $query = [
            'offerId' => $offerId,
            'dateFrom' => $startDate->format('Y-m-d'),
            'dateTo' => $endDate->format('Y-m-d'),
        ];
        $url = self::BASE_URL . self::URI_VIEWS_BY_DATES . '?' . http_build_query($query);

        $response = $this->get($url, $this->getHeaders());
        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return ViewsByDatesObject::createFromArray($data['result']);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return ComplaintListObject
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getComplaints(int $page, int $limit): ComplaintListObject
    {
        $query = [
            'pageSize' => $limit,
            'page' => $page,
        ];
        $url = self::BASE_URL . self::URI_COMPLAINTS . '?' . http_build_query($query);
        $response = $this->get($url, $this->getHeaders());
        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return ComplaintListObject::createFromArray($data['result']);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return NotificationListObject
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getNotifications(int $page, int $limit): NotificationListObject
    {
        $query = [
            'pageSize' => $limit,
            'page' => $page,
        ];
        $url = self::BASE_URL . self::URI_NOTIFICATIONS . '?' . http_build_query($query);
        $response = $this->get($url, $this->getHeaders());
        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return NotificationListObject::createFromArray($data['result']);
    }

    /**
     * @return array
     */
    protected function getHeaders(): array
    {
        return [
            'headers' => [
                'Authorization' => 'Bearer ' . config('integrations.cian.access_token')
            ],
        ];
    }
}
