<?php


namespace App\Services\Xml;

use App\Models\Block;
use App\Models\Building;
use Carbon\Carbon;

class Yandex extends BaseXmlService
{
    protected const TYPE = 'аренда';
    protected const PROPERTY_TYPE = 'жилая';
    protected const CATEGORY = 'квартира';
    protected const COUNTRY = 'Россия';
    protected const AGENT_CATEGORY = 'агентство';
    protected const PRICE_PERIOD = 'месяц';
    protected const UNIT_AREA = 'кв. м';

    protected const RENOVATION_NEED = 'требует ремонта';
    protected const RENOVATION_EURO = 'евроремонт';
    protected const RENOVATION_COSMETIC = 'косметический';
    protected const RENOVATION_DESIGN = 'дизайнерский';

    protected const ROOM_TYPE_SEPARATED = 'раздельные';
    protected const ROOM_TYPE_COMBINED = 'смежные';

    protected const WINDOW_VIEW_YARD = 'во двор';
    protected const WINDOW_VIEW_STREET = 'на улицу';

    protected const WC_SEPARATED = 'раздельный';
    protected const WC_COMBINED = 'совмещенный';

    protected const BUILDING_TYPE_BLOCK = 'блочный';
    protected const BUILDING_TYPE_WOOD = 'деревянный';
    protected const BUILDING_TYPE_BRICK = 'кирпичный';
    protected const BUILDING_TYPE_MONOLITH_BRICK = 'кирпично-монолитный';
    protected const BUILDING_TYPE_MONOLITH = 'монолит';
    protected const BUILDING_TYPE_PANEL = 'панельный';

    /**
     *
     */
    public function generate(): void
    {
        $root = new Doc();

        $feed = $root->attach('realty-feed');
        $feed->setAttribute('xmlns', 'http://webmaster.yandex.ru/schemas/feed/realty/2010-06');

        Block::query()
            ->where(['status' => Block::STATUS_ACTIVE, 'out_of_market' => 0])
            ->whereNotNull('yandex_promo')
            ->with(['building', 'building.metro'])
            ->groupBy('blocks.id')
            ->chunk(10, function ($blocks) use ($feed) {
                foreach ($blocks as $block) {
                    $this->attachAd($feed, $block);
                }
            });


        $root->formatOutput = true;
        $root->save(Doc::getFolder() . 'yandex.xml');
    }

    /**
     * @param Node $feed
     * @param Block $block
     * @throws \Exception
     */
    protected function attachAd(Node $feed, Block $block): void
    {
        $offer = $feed->attach('offer');
        $offer->setAttribute('internal-id', $block->id);
        $offer->attach('type', self::TYPE);
        $offer->attach('property-type', self::PROPERTY_TYPE);
        $offer->attach('category', self::CATEGORY);
        $offer->attach('creation-date', $block->created_at->format(Carbon::ATOM));
        if ($block->deposit) {
            $offer->attach('rent-pledge', 1);
        }
        $offer->attach('description', $block->cian_feed_description);

        $this->attachPromo($offer, $block);
        $this->attachLocation($offer, $block);
        $this->attachAgent($offer);
        $this->attachPrice($offer, $block);
        $this->attachArea($offer, $block);
        $this->attachPhoto($offer, $block);
        $this->attachVideo($offer, $block);
        $this->attachRenovation($offer, $block);
        $this->attachInformation($offer, $block);
        $this->attachBuilding($offer, $block);
    }

    /**
     * @param Node $offer
     * @param Block $block
     */
    protected function attachBuilding(Node $offer, Block $block): void
    {
        $offer->attach('floors-total', $block->building->floors);

        //TODO YANDEX BUILDING IMPORT
//        $offer->attach('yandex-building-id',1);
//        $offer->attach('yandex-house-id',1);

        $type = null;
        switch ($block->building->type) {
            case Building::TYPE_BRICK:
                $type = self::BUILDING_TYPE_BRICK;
                break;
            case Building::TYPE_MONOLITH:
                $type = self::BUILDING_TYPE_MONOLITH;
                break;
            case Building::TYPE_PANEL:
                $type = self::BUILDING_TYPE_PANEL;
                break;
            case Building::TYPE_BLOCK:
                $type = self::BUILDING_TYPE_BLOCK;
                break;
            case Building::TYPE_WOOD:
                $type = self::BUILDING_TYPE_WOOD;
                break;
            case Building::TYPE_MONOLITH_BRICK:
                $type = self::BUILDING_TYPE_MONOLITH_BRICK;
                break;
        }
        if ($type) {
            $offer->attach('building-type', $type);
        }
        if ($block->building->year_construction) {
            $offer->attach('built-year', $block->building->year_construction);
        }
        if ($block->building->ceil_height) {
            $offer->attach('ceiling-height', $block->building->ceil_height);
        }
    }

    /**
     * @param Node $offer
     * @param Block $block
     */
    protected function attachInformation(Node $offer, Block $block): void
    {
        if ($block->rooms !== Block::ROOM_STUDIO) {
            if ($block->rooms === Block::ROOM_FREE_PLANNING) {
                $offer->attach('rooms', 2);
                $offer->attach('open-plan', 1);
            } else {
                $offer->attach('rooms', $block->rooms);
            }
        } else {
            $offer->attach('studio', 1);
        }
        $offer->attach('floor', $block->floor);
        if ($block->type === Block::TYPE_APARTMENT) {
            $offer->attach('apartments', 1);
        }
        if ($block->rooms_type === Block::ROOM_TYPE_SEPARATE) {
            $offer->attach('rooms-type', self::ROOM_TYPE_SEPARATED);
        } elseif ($block->rooms_type === Block::ROOM_TYPE_COMBINED) {
            $offer->attach('rooms-type', self::ROOM_TYPE_COMBINED);
        }

        if ($block->windowsInOut === Block::WINDOW_YARD) {
            $offer->attach('window-view', self::WINDOW_VIEW_YARD);
        } elseif ($block->windowsInOut === Block::WINDOW_STREET) {
            $offer->attach('window-view', self::WINDOW_VIEW_STREET);
        }
        if ($block->balcony) {
            switch ($block->balcony) {
                case Block::BALCONY_1:
                    $offer->attach('balcony', 'балкон');
                    break;
                case Block::BALCONY_2:
                    $offer->attach('balcony', '2 балкона');
                    break;
                case Block::BALCONY_3:
                    $offer->attach('balcony', '3 балкона');
                    break;
                case Block::BALCONY_4:
                    $offer->attach('balcony', '4 балкона');
                    break;
                case Block::BALCONY_5:
                    $offer->attach('balcony', '5 балконов');
                    break;
            }
        }
        if ($block->separate_wc_count) {
            $offer->attach('bathroom-unit', self::WC_SEPARATED);
        } elseif ($block->combined_wc_count) {
            $offer->attach('bathroom-unit', self::WC_COMBINED);
        }

        if (in_array(Block::FILLING_AIR_CONDITIONING, $block->filling)) {
            $offer->attach('air-conditioner', 1);
        }
        if (in_array(Block::FILLING_PHONE, $block->filling)) {
            $offer->attach('phone', 1);
        }
        if (in_array(Block::FILLING_INTERNET, $block->filling)) {
            $offer->attach('internet', 1);
        }
        if (in_array(Block::FILLING_ROOM_FURNITURE, $block->filling)) {
            $offer->attach('room-furniture', 1);
        }
        if (in_array(Block::FILLING_KITCHEN_FURNITURE, $block->filling)) {
            $offer->attach('kitchen-furniture', 1);
        }
        if (in_array(Block::FILLING_TV, $block->filling)) {
            $offer->attach('television', 1);
        }
        if (in_array(Block::FILLING_WASHING_MACHINE, $block->filling)) {
            $offer->attach('washing-machine', 1);
        }
        if (in_array(Block::FILLING_DISHWASHER, $block->filling)) {
            $offer->attach('dishwasher', 1);
        }
        if (in_array(Block::FILLING_REFRIGERATOR, $block->filling)) {
            $offer->attach('refrigerator', 1);
        }

        if (in_array(Block::LIVING_COND_NO_CHILDREN, $block->living_conds)) {
            $offer->attach('with-children', 0);
        } else {
            $offer->attach('with-children', 1);
        }
        if (in_array(Block::LIVING_COND_NO_ANIMALS, $block->living_conds)) {
            $offer->attach('with-pets', 0);
        } else {
            $offer->attach('with-pets', 1);
        }
    }

    /**
     * @param Node $offer
     * @param Block $block
     * @throws \Exception
     */
    protected function attachRenovation(Node $offer, Block $block): void
    {
        switch ($block->renovation) {
            case Block::RENOVATION_NO:
                $renovation = self::RENOVATION_NEED;
                break;
            case Block::RENOVATION_COSMETIC:
                $renovation = self::RENOVATION_COSMETIC;
                break;
            case Block::RENOVATION_DESIGN:
                $renovation = self::RENOVATION_DESIGN;
                break;
            case Block::RENOVATION_EURO:
                $renovation = self::RENOVATION_EURO;
                break;
            default:
                throw new \Exception('Unprocessable renovation for yandex feed');
        }
        $offer->attach('renovation', $renovation);
    }

    /**
     * @param Node $offer
     * @param Block $block
     */
    protected function attachVideo(Node $offer, Block $block): void
    {
        if (!$block->video_url) {
            return;
        }

        $video = $offer->attach('video-review');
        $video->attach('youtube-video-review-url', $block->video_url);
        $video->attach('online-show', 0);
    }

    /**
     * @param Node $offer
     * @param Block $block
     */
    protected function attachPhoto(Node $offer, Block $block): void
    {
        foreach ($block->simplePhotos as $simplePhoto) {
            $offer->attach('image', $simplePhoto->getPreviewAttribute());
        }
    }

    /**
     * @param Node $offer
     * @param Block $block
     */
    protected function attachArea(Node $offer, Block $block): void
    {
        $area = $offer->attach('area');
        $area->attach('value', $block->area);
        $area->attach('unit', self::UNIT_AREA);

        if ($block->living_area) {
            $livingArea = $offer->attach('living-space');
            $livingArea->attach('value', $block->living_area);
            $livingArea->attach('unit', self::UNIT_AREA);
        }

        if ($block->kitchen_area) {
            $kitchenArea = $offer->attach('kitchen-space');
            $kitchenArea->attach('value', $block->kitchen_area);
            $kitchenArea->attach('unit', self::UNIT_AREA);
        }
    }

    /**
     * @param Node $offer
     * @param Block $block
     */
    protected function attachPrice(Node $offer, Block $block): void
    {
        $price = $offer->attach('price');
        $price->attach('value', (int)$block->cost);
        $price->attach('currency', $block->currency_description);
        $price->attach('period', self::PRICE_PERIOD);
    }

    /**
     * @param Node $offer
     */
    protected function attachAgent(Node $offer): void
    {
        $agent = $offer->attach('sales-agent');
        $agent->attach('phone', $this->settings->getPhoneYandex());
        $agent->attach('category', self::AGENT_CATEGORY);
    }

    /**
     * @param Node $offer
     * @param Block $block
     */
    protected function attachLocation(Node $offer, Block $block): void
    {
        $location = $offer->attach('location');
        $location->attach('country', self::COUNTRY);
        if ($block->building->address_region === 'Московская') {
            $location->attach('region', 'Московская область');
        } else {
            $location->attach('region', $block->building->address_region);
        }
        $location->attach('district', $block->building->region->name);
        if ($block->building->address_city) {
            $location->attach('locality-name', $block->building->address_city);
        } else {
            $location->attach('locality-name', $block->building->address_region);
        }
        if ($block->building->address_street && $block->building->address_house) {
            $location->attach(
                'address',
                "{$block->building->address_street} ул., д. {$block->building->address_house}"
            );
        }
        $location->attach('apartment', $block->flat_number ?: 111);
    }

    /**
     * @param Node $offer
     * @param Block $block
     */
    protected function attachPromo(Node $offer, Block $block): void
    {
        if ($block->yandex_promo === Block::YANDEX_PROMO_FREE) {
            return;
        }
        $vas = $offer->attach('vas', Block::YANDEX_PROMOS[$block->yandex_promo]);
        if ($block->yandex_promo === Block::YANDEX_PROMO_RAISE) {
            $vas->setAttribute('start-time', Carbon::now()->format(Carbon::ATOM));
            $vas->setAttribute('schedule', 'everyday');
        }
    }
}
