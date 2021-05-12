<?php


namespace App\Services\Xml;

use App\Models\Block;
use App\Models\Building;
use App\Services\Models\NotificationService;
use Carbon\Carbon;

class Avito extends BaseXmlService
{
    protected const VERSION = 3;
    protected const TARGET = 'Avito.ru';

    public function generate()
    {
        $root = new Doc();
        $ads = $root->attach('Ads');
        $ads->setAttribute('formatVersion', self::VERSION);
        $ads->setAttribute('target', self::TARGET);

        Block::query()
            ->where(['status' => Block::STATUS_ACTIVE, 'out_of_market' => 0])
            ->whereNotNull('avito_promo')
            ->with(['building', 'building.metro'])
            ->groupBy('blocks.id')
            ->chunk(10, function ($blocks) use ($ads) {
                foreach ($blocks as $block) {
                    $this->attachAd($ads, $block);
                }
            });


        $root->formatOutput = true;
        $root->save(Doc::getFolder() . 'avito.xml');
    }

    protected function attachAd(Node $ads, Block $block)
    {
        $houseType = $this->getHouseType($block);
        $rooms = $this->getRooms($block);
        $message = 'Не удалось отправить обьект: ' . $block->id . ' в АВИТО ФИД. Причина: ';
        if (!$rooms) {
            $message .= 'Квартира имеет свободную плнировку. Не поддерживается в АВИТО.';
            $this->notificationsService->createTolkoNotificationWithoutDuplicate($message, $block->id);
            return;
        }
        $deposit = $this->getDeposit($block);
        if (!$deposit) {
            $message .= "Необрабатываемый срок залога. Стоимость: {$block->cost}, Залог: {$block->deposit}";
            $this->notificationsService->createTolkoNotificationWithoutDuplicate($message, $block->id);
            return;
        }
        $data = [
            'Id' => $block->id,
            'DateBegin' => Carbon::now()->format(\DateTime::ATOM),
            'AdStatus' => Block::AVITO_PROMOS[$block->avito_promo],
            'AllowEmail' => 'Да',
            'ContactPhone' => $this->settings->getPhoneAvito(),
            'PropertyRights' => 'Посредник',
            'Address' => $block->building->address,
            'Description' => $block->cian_feed_description,
            'Category' => 'Квартиры',
            'OperationType' => 'Сдам',
            'Price' => (int)$block->cost,
            'PriceType' => 'в месяц',
            'Rooms' => $rooms,
            'Square' => $block->area,
            'KitchenSpace' => $block->kitchen_area,
            'LivingSpace' => $block->living_area,
            'Floor' => $block->floor,
            'Floors' => $block->building->floors,
            'HouseType' => $houseType,
            'BalconyOrLoggia' => $block->balcony ? 'Балкон' : 'Нет',
            'LeaseCommissionSize' => $block->client_commission_percent,
            'LeaseDeposit' => $deposit,
            'LeaseType' => 'На длительный срок',
            'VideoURL' => $block->video_url,
        ];
        $ad = $ads->attach('Ad');
        foreach ($data as $nodeName => $nodeValue) {
            $ad->attach($nodeName, $nodeValue);
        }
        $viewFromWindows = $ad->attach('ViewFromWindows');
        $this->attachViewFromWindows($viewFromWindows, $block);
        $multimedia = $ad->attach('LeaseMultimedia');
        $this->attachLeaseMultimedia($multimedia, $block);
        $leaseAppliances = $ad->attach('LeaseAppliances');
        $this->attachLeaseAppliances($leaseAppliances, $block);
        $comfort = $ad->attach('LeaseComfort');
        $this->attachComfort($comfort, $block);
        $additionally = $ad->attach('LeaseAdditionally');
        $this->attachAdditionally($additionally, $block);
        $images = $ad->attach('Images');
        $this->attachImages($images, $block);
    }

    /**
     * @param Node $images
     * @param Block $block
     */
    protected function attachImages(Node $images, Block $block): void
    {
        foreach ($block->simplePhotos as $simplePhoto) {
            $image = $images->attach('Image');
            $image->setAttribute('url', $simplePhoto->getPreviewAttribute());
        }
        foreach ($block->planPhotos as $planPhoto) {
            $image = $images->attach('Image');
            $image->setAttribute('url', $planPhoto->getPreviewAttribute());
        }
    }

    /**
     * @param Node $additionally
     * @param Block $block
     */
    protected function attachAdditionally(Node $additionally, Block $block): void
    {
        if (!in_array(Block::LIVING_COND_NO_ANIMALS, $block->living_conds)) {
            $additionally->attach('Option', 'Можно с питомцами');
        }
        if (!in_array(Block::LIVING_COND_NO_CHILDREN, $block->living_conds)) {
            $additionally->attach('Option', 'Можно с детьми');
        }
    }

    /**
     * @param Node $comfort
     * @param Block $block
     */
    protected function attachComfort(Node $comfort, Block $block): void
    {
        if (in_array(Block::FILLING_AIR_CONDITIONING, $block->filling, true)) {
            $comfort->attach('Option', 'Кондиционер');
        }
        if ($block->balcony) {
            $comfort->attach('Option', 'Балкон / лоджия');
        }
    }

    /**
     * @param Node $leaseAppliances
     * @param Block $block
     */
    protected function attachLeaseAppliances(Node $leaseAppliances, Block $block): void
    {
        foreach ($block->filling as $value) {
            switch ($value) {
                case Block::FILLING_REFRIGERATOR:
                    $leaseAppliances->attach('Option', 'Холодильник');
                    break;
                case Block::FILLING_WASHING_MACHINE:
                    $leaseAppliances->attach('Option', 'Стиральная машина');
                    break;
                case Block::FILLING_KITCHEN_FURNITURE:
                    $leaseAppliances->attach('Option', 'Плита');
                    break;
            }
        }
    }

    /**
     * @param Node $multimedia
     * @param Block $block
     */
    protected function attachLeaseMultimedia(Node $multimedia, Block $block): void
    {
        foreach ($block->filling as $value) {
            switch ($value) {
                case Block::FILLING_INTERNET:
                    $multimedia->attach('Option', 'Wi-FI');
                    break;
                case Block::FILLING_TV:
                    $multimedia->attach('Option', 'Телевизор');
                    break;
            }
        }
    }

    /**
     * @param Node $node
     * @param Block $block
     */
    protected function attachViewFromWindows(Node $node, Block $block): void
    {
        if (!$block->windowsInOut) {
            return;
        }
        if ($block->windowsInOut === Block::WINDOW_STREET) {
            $node->attach('Option', 'На улицу');
        } elseif ($block->windowsInOut === Block::WINDOW_YARD) {
            $node->attach('Option', 'Во двор');
        } elseif ($block->windowsInOut === Block::WINDOW_YARD_AND_STREET) {
            $node->attach('Option', 'На улицу');
            $node->attach('Option', 'Во двор');
        }
    }

    /**
     * @param Block $block
     * @return string|null
     */
    protected function getHouseType(Block $block): ?string
    {
        switch ($block->building->type) {
            case Building::TYPE_BRICK:
            case Building::TYPE_MONOLITH_BRICK:
                return 'Кирпичный';
            case Building::TYPE_MONOLITH:
                return 'Монолитный';
            case Building::TYPE_PANEL:
                return 'Панельный';
            case Building::TYPE_BLOCK:
                return 'Блочный';
            case Building::TYPE_WOOD:
                return 'Деревянный';
        }

        return 'Панельный';
    }


    /**
     * @param Block $block
     * @return int|string
     */
    protected function getRooms(Block $block)
    {
        if ($block->rooms === Block::ROOM_STUDIO) {
            return 'Студия';
        }
        if ($block->rooms === Block::ROOM_FREE_PLANNING) {
            return null;
        }

        return $block->rooms;
    }

    /**
     * @param Block $block
     * @return string|null
     */
    protected function getDeposit(Block $block): ?string
    {
        if (!$block->deposit) {
            return 'Без залога';
        }

        $depositPercent = $block->deposit / $block->cost;
        $month = round($depositPercent * 2) / 2;


        $deposit = [
            '0' => '0,5 месяца',
            '0.5' => '0,5 месяца',
            '1' => '1 месяц',
            '1.5' => '1,5 месяца',
            '2' => '2 месяца',
            '2.5' => '2,5 месяца',
            '3' => '3 месяца',
        ];


        foreach ($deposit as $key => $value) {
            if ((float)$key === $month) {
                return $value;
            }
        }

        return null;
    }
}
