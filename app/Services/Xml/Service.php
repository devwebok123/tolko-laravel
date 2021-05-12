<?php

namespace App\Services\Xml;

use App\Models\Block;
use App\Models\BlockPhoto;
use App\Models\Building;

class Service extends BaseXmlService
{

    public function cian()
    {
        $root = new Doc;
        $feed = $root->attach("feed");
        $feed->attach('feed_version', 2);

        Block::query()
            ->where(['status' => Block::STATUS_ACTIVE, 'out_of_market' => 0])
            ->whereNotNull('cian')
            ->with(['building', 'building.metro'])
            ->groupBy('blocks.id')
            ->chunk(100, function ($blocks) use ($feed) {
                foreach ($blocks as $block) {
                    $this->cianFeedAttachListingItem($feed, $block);
                }
            });

        $root->formatOutput = true;
        $root->save(Doc::getFolder() . 'cian.xml');
    }

    /**
     * @param Node $feed
     * @param Block $block
     * @return bool
     */
    protected function cianFeedAttachListingItem(Node $feed, Block $block): bool
    {
        $b = $block->building;
        $object = $feed->attach('object');

        $params = array_filter([
            'ExternalId' => $block->id,
            'Description' => htmlspecialchars($block->cian_feed_description),
            'Address' => $b->address,
            'Phones' => [
                'PhoneSchema' => [
                    'CountryCode' => substr($this->settings->getPhoneCian(), 0, 2),
                    'Number' => substr($this->settings->getPhoneCian(), 2),
                ]
            ],
            'SubAgent' => [
                'email' => 'ab@tolko.ru',
                'Phone' => $this->settings->getPhoneCian(),
            ],
            //LayoutPhoto
            //Photo
            'Title' => $block->ad_title,
            'Auction' => [
                'bet' => $block->bet
            ],
            'CadastralNumber' => $block->cadastral_number,
            'Category' => 'flatRent',
            'RoomType' => Block::ROOM_TYPES[$block->rooms_type], //todo wtf default(0)
            'FlatRoomsCount' => $block->rooms,
            'IsApartments' => $block->type == Block::TYPE_APARTMENT ? 'true' : null,
            'TotalArea' => $block->area,
            'FloorNumber' => $block->floor,
            'LivingArea' => $block->living_area,
            'KitchenArea' => $block->kitchen_area,
            'LoggiasCount' => $block->balcony,
            'WindowsViewType' => Block::WINDOWS[$block->windowsInOut],
            'SeparateWcsCount' => $block->separate_wc_count,
            'CombinedWcsCount' => $block->combined_wc_count,
            'RepairType' => Block::RENOVATIONS[$block->renovation],
            'ChildrenAllowed' => !in_array(Block::LIVING_COND_NO_CHILDREN, $block->living_conds) &&
            !in_array(Block::LIVING_COND_ONLY_ONE, $block->living_conds) ? 'true' : null,
            'PetsAllowed' => !in_array(Block::LIVING_COND_NO_ANIMALS, $block->living_conds) ? 'true' : null,

        ], function ($value) {
            return !empty($value) || $value === 0;
        });
        if ($block->video_url) {
            $params['Videos'] = ['VideoSchema' => ['Url' => $block->video_url]];
        }
        if ($block->filling) {
            foreach ($block->filling as $f) {
                $params[Block::FILLINGS[$f]] = 'true';
            }
        }
        $object->rAttach($params);

        if ($b->metro_id && $b->metro->cian_id) {
            $undergroundInfoSchema = array_filter([
                'TransportType' => Building::TIME_TYPES[$b->metro_time_type] ??
                    !empty($b->metro_time_type),
                'Time' => $b->metro_time,
                'Id' => $b->metro->cian_id
            ], function ($value) {
                return !empty($value);
            });

            if (!empty($undergroundInfoSchema)) {
                $object->rAttach(['Undergrounds' => ['UndergroundInfoSchema' => $undergroundInfoSchema]]);
            }
        }

        $promo = Block::CIAN_PROMOS[$block->cian];

        $attrs['PublishTerms'] = [
            'Terms' => [
                'PublishTermSchema' => [
                    'Services' => [
                        'ServicesEnum' => $promo
                    ]
                ]
            ]
        ];

        $object->rAttach($attrs);

        $photos = $object->attach('Photos');
        if (!empty($photos)) {
            $block->simplePhotos->map(function (BlockPhoto $photo) use (&$photos) {
                $photos->attach('PhotoSchema')->attach('FullUrl', $photo->preview);
            });
            $block->planPhotos->map(function (BlockPhoto $photo) use (&$photos) {
                $photos->attach('PhotoSchema')->attach('FullUrl', $photo->preview);
            });
        }

        /* !Common */

        $buildingData = array_filter([
            'Name' => $b->name,
            'FloorsCount' => $b->floors,
            'CeilingHeight' => $b->ceil_height,
            // 'MaterialType' => $b->type ? Building::TYPES[$b->type] : null,
            'Series' => $b->series,
            'PassengerLiftsCount' => $b->passenger_lift_count,
            'CargoLiftsCount' => $b->cargo_lift_count,
            'HasGarbageChute' => $b->garbage_chute,
        ], function ($value) {
            return !empty($value);
        });
        if ($b->parking_type) {
            $buildingData['Parking']['type'] = Building::PARKING_TYPES[$b->parking_type];
        }
        if (!empty($buildingData)) {
            $object->rAttach(['Building' => $buildingData]);
        }

        $bargainTerms = array_filter([
            'Price' => (int)$block->cost,
            'UtilitiesTerms' => [
                'IncludedInPrice' => in_array(Block::INCLUDED_UTILITY, $block->included) ? 'true' : null,
                'FlowMetersNotIncludedInPrice' => !in_array(Block::INCLUDED_METERS, $block->included) ? 'true' : null,
            ],
            'Currency' => strtolower(Block::CURS[$block->currency]),
            // 'VatType' => $block::CIAN_TAXATIONS[$block->taxation],
            // 'SecurityDeposit' => $block->cost,
            'BargainAllowed' => !is_null($block->bargain),
            'LeaseTermType' => 'longTerm',
            'PrepayMonths' => 1,
            'Deposit' => (int)$block->deposit,
        ], function ($value) {
            return !empty($value);
        });
        $bargainTerms['AgentFee'] =
            $block->commission_type === Block::COMMISSION_TYPE_CLIENT ? (int)$block->commission : 0;
        $bargainTerms['ClientFee'] = $bargainTerms['AgentFee'];
        $bargainTerms['PaymentPeriod'] = 'monthly';

        if (!empty($bargainTerms)) {
            $object->rAttach(['BargainTerms' => $bargainTerms]);
        }

        return true;
    }
}
