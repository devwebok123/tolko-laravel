<?php

namespace App\Services;

use App\Exceptions\DadataException;
use App\Models\AdmArea;
use App\Models\Line;
use App\Models\Region;
use App\Models\Metro;
use Illuminate\Support\Facades\Cache;

class DadataService
{
    /**
     * @var array
     */
    public static $data;
    /** @var array $lines */
    protected static $lines = [];

    /**
     * @param $address string
     * @return array
     * @throws DadataException
     */
    public function getAddressSuggest(string $address): array
    {
        $payload = '{"locations":[{"region":"Москва"},{"kladr_id":"50"}],"query":"' . $address . '","count":10}';

        $data = self::postReq('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address', $payload);

        $list = [];
        if (isset($data['suggestions'])) {
            foreach ($data['suggestions'] as $item) {
                $list[] = $item['value'];
            }
        }

        return $list;
    }

    /**
     * @param $address string
     * @return array
     * @throws DadataException
     */
    public function getMetroStationSuggest(string $station): array
    {
        $payload = '{"filters":[{"city":"Москва"}], "query":"' . $station . '","count":10}';

        $data = self::postReq('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/metro', $payload);

        $list = [];
        if (isset($data['suggestions'])) {
            foreach ($data['suggestions'] as $item) {
                $list[] = $item['value'];
            }
        }

        return $list;
    }

    /**
     * @param $address string
     * @return array
     * @throws DadataException
     */
    public static function parseAddress(string $address): array
    {
        $key = md5('parseAddress_' . $address);
        $data = Cache::get($key);

        if ($data === null) {
            $payload = '[ "' . $address . '" ]';

            $data = self::postReq('https://cleaner.dadata.ru/api/v1/clean/address', $payload);

            Cache::put($key, $data, 60 * 60 * 24 * 365); // 1 year
        }
        return $data;
    }

    /**
     * @param string $url
     * @param string $payload
     * @return mixed
     * @throws DadataException
     */
    private static function postReq(string $url, string $payload)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            'Authorization: Token ' . config('dadata.token'),
            'X-Secret: ' . config('dadata.secret'),
        ]);

        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);

        self::handleException($data);

        return $data;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function isMoscowRegion(array $data): bool
    {
        return (isset($data[0]['region_iso_code']) && $data['0']['region_iso_code'] === 'RU-MOW');
    }

    /**
     * @param string $address
     * @return array
     * @throws DadataException
     */
    public static function addressInfo(string $address): array
    {
        $errors = [];

        $data = self::parseAddress($address)[0];

        $isMoscow = $data['region_with_type'] === 'г Москва' ? 1 : 0;
        $admArea = null;
        $region = null;
        $metroDistance = null;
        $metro = null;
        $metro2 = null;
        $metro3 = null;
        $addressStreet = null;
        $mkad = $data['beltway_distance'] ?? null;

        if (!empty($data['city_area'])) {
            $admArea = AdmArea::firstOrCreate(['name' => $data['city_area']]);

            if (!empty($data['city_district'])) {
                $region = Region::firstOrCreate(['adm_area_id' => $admArea->id, 'name' => $data['city_district']]);
            } else {
                $errors[] = "Для адреса '{$address}' регион не найден";
            }
        }

        if (!in_array($data['region'], ['Московская', 'Москва'])) {
            $errors[] = $address . ' за пределами мск.обл';
        }

        if (!empty($data['metro'][0])) {
            $metro = Metro::where('name', $data['metro'][0]['name'])
                ->where('line', self::metroLine($data['metro'][0]['line']))
                ->first();
            if (!$metro) {
                if ($metro = Metro::where('name', $data['metro'][0]['name'])->first()) {
                    $errors[] = 'Осторожно у метро: ' . $data['metro'][0]['name'] . ' сменена ветка с '
                        . $data['metro'][0]['line'] . ' на ' . self::getLines()[$metro->line] . "(address: $address)";
                }
            }

            if (!empty($metro)) {
                $metroDistance = (int)($data['metro'][0]['distance'] * 1000);
            } else {
                $errors[] = 'Метро: ' . $data['metro'][0]['name'] . ' / ' .
                    $data['metro'][0]['line'] . ' не найдено в БД' . "(address: $address)";
            }

            if (!empty($data['metro'][1])) {
                $metro2 = Metro::where('name', $data['metro'][1]['name'])
                    ->where('line', self::metroLine($data['metro'][1]['line']))
                    ->first();
            }

            if (!empty($data['metro'][2])) {
                $metro3 = Metro::where('name', $data['metro'][2]['name'])
                    ->where('line', self::metroLine($data['metro'][2]['line']))
                    ->first();
            }
        }

        $noMoscowRegion = Region::whereName('Московская область')->first();
        if (!$isMoscow) {
            $region = $noMoscowRegion;
        }

        return [
            'address' => $data['result'],
            'adm_area_id' => $admArea ? $admArea->id : null,
            'region_id' => $region ? $region->id : null,
            'metro_id' => $metro ? $metro->id : null,
            'metro_id_2' => $metro2 ? $metro2->id : null,
            'metro_id_3' => $metro3 ? $metro3->id : null,
            'metro_distance' => $metroDistance,
            'mkad_distance' => $mkad,
            'lat' => $data['geo_lat'],
            'lon' => $data['geo_lon'],
            'address_street' => $data['street'],
            'error' => implode("\n", $errors),
        ];
    }

    /**
     * @param array $data
     * @return void
     * @throws DadataException
     */
    protected static function handleException(array $data): void
    {
        if (isset($data['status']) && $data['status'] !== 200) {
            throw new DadataException($data['message'], $data['status']);
        }
    }

    protected static function metroLine(string $name)
    {
        return array_search($name, self::getLines());
    }

    /**
     * @return array
     */
    protected static function getLines():array
    {
        if (self::$lines) {
            return self::$lines;
        }

        self::$lines = Line::all()->pluck('name')->toArray();

        return self::$lines;
    }
}
