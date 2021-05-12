<?php


namespace App\Clients;

use App\DataObjects\AdsApi\Announcements\Announcement;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class AdsApiClient extends Client
{
    protected const BASE_URL = 'https://ads-api.ru/';

    protected const URI_GET_ANNOUNCEMENT = 'main/api';

    protected const MIN_PRICE = 35000;
    protected const MAX_PRICE = 150000;

    protected const PERSON_TYPE_INDIVIDUAL = 1;
    protected const PERSON_TYPE_AGENCY = 2;
    protected const PERSON_TYPE_OWNER = 3;

    protected const CITY_MOSCOW = 'Москва';

    protected const CATEGORY_FLAT = 2;

    protected const NEDVIZHIMOST_TYPE_SELL = 1;
    protected const NEDVIZHIMOST_TYPE_RENT = 2;
    protected const NEDVIZHIMOST_TYPE_BUY = 3;
    protected const NEDVIZHIMOST_TYPE_TAKE_OFF = 4;

    protected const SOURCE_AVITO = 1;
    protected const SOURCE_IRR = 2;
    protected const SOURCE_REALTY_YANDEX = 3;
    protected const SOURCE_CIAN = 4;
    protected const SOURCE_SOB = 5;
    protected const SOURCE_YOULA = 6;
    protected const SOURCE_N1 = 7;
    protected const SOURCE_MOYAREKLAMA = 10;

    protected const AVAILABLE_SOURCES = [
        self::SOURCE_AVITO,
        self::SOURCE_IRR,
        self::SOURCE_REALTY_YANDEX,
        self::SOURCE_CIAN,
        self::SOURCE_YOULA,
    ];

    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array|Announcement[]
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getAnnouncements(Carbon $startDate, Carbon $endDate): array
    {
        $params = array_merge(
            [
                'date1' => $startDate->format('Y-m-d H:i:s'),
                'date2' => $endDate->format('Y-m-d H:i:s')
            ],
            $this->defaultParams()
        );

        $url = self::BASE_URL . self::URI_GET_ANNOUNCEMENT . '?' . http_build_query($params);

        $response = $this->get($url);
        $content = $response->getBody()->getContents();
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $result = [];
        foreach ($data['data'] as $object) {
            $result[] = Announcement::createFromArray($object);
        }

        return $result;
    }


    /**
     * @return array
     */
    protected function defaultParams(): array
    {
        return [
            'user' => config('integrations.ads_api.client'),
            'token' => config('integrations.ads_api.access_toke'),
            'price1' => self::MIN_PRICE,
            'price2' => self::MAX_PRICE,
            'person_type' => self::PERSON_TYPE_INDIVIDUAL,
            'city' => self::CITY_MOSCOW,
            'category_id' => self::CATEGORY_FLAT,
            'nedvigimost_type' => self::NEDVIZHIMOST_TYPE_RENT,
            'source' => implode(',', self::AVAILABLE_SOURCES),
        ];
    }
}
