<?php


namespace App\Services\AdsApi;

use App\Clients\AdsApiClient;
use App\DataObjects\AdsApi\Announcements\Announcement;
use App\Exceptions\DadataException;
use App\Models\Block;
use App\Models\BlockPhoto;
use App\Models\Building;
use App\Models\Contact;
use App\Services\DadataService;
use App\Services\Models\BlockPhotoService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImportBlockService
{
    /** @var AdsApiClient $client */
    protected $client;
    /** @var DadataService $dadaService */
    protected $dadaService;
    /** @var BlockPhotoService $photoService */
    protected $photoService;

    public function __construct(AdsApiClient $client, DadataService $dadaService, BlockPhotoService $photoService)
    {
        $this->client = $client;
        $this->dadaService = $dadaService;
        $this->photoService = $photoService;
    }

    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function import(Carbon $startDate, Carbon $endDate): void
    {
        $announcements = $this->client->getAnnouncements($startDate, $endDate);

        foreach ($announcements as $announcement) {
            $this->processAnnouncement($announcement);
        }
    }

    /**
     * @param Announcement $announcement
     */
    protected function processAnnouncement(Announcement $announcement): void
    {
        $block = Block::whereAdsApiId($announcement->getId())->first();
        if (!$block) {
            $block = new Block();
        }
        $this->appplyParams($block, $announcement);
    }

    /**
     * @param string $address
     * @param string $city
     * @return Building|null
     * @throws DadataException
     */
    protected function getBuilding(string $city, string $address): ?Building
    {
        $full = $city . ', ' . $address;

        $building = Building::whereAdsApiAddress($full)->first();
        if ($building) {
            return $building;
        }
        $parseAddress = $this->dadaService->getAddressSuggest($full);

        /** @var Building|null $building */
        $building = Building::whereAddressAddress($parseAddress[0])->first();
        if (!$building) {
            return null;
        }

        $building->ads_api_address = $full;
        $building->save();

        return $building;
    }

    protected function appplyParams(Block $block, Announcement $announcement): ?Block
    {
        $building = $this->getBuilding($announcement->getCity(), $announcement->getAddress());
        if (!$building) {
            $fullAddress = $announcement->getCity() . ', ' . $announcement->getAddress();
            Storage::disk('local')->append('undefined_addresses.log', $fullAddress);
            return null;
        }

        $block->building_id = $building->id;
        $block->ads_api_id = $announcement->getId();
        $block->cost = $announcement->getPrice();
        $block->description = $announcement->getDescription();
        $block->kitchen_area = $announcement->getParams()->getKitchenArea();
        $block->floor = $announcement->getParams()->getFloor();
        $block->living_area = $announcement->getParams()->getLivingArea();
        $block->commission = $announcement->getParams()->getCommission();
        $block->rooms = $announcement->getParams()->getRoomsCount();
        $block->area = $announcement->getParams()->getArea();
        $block->status = Block::STATUS_DRAFT;

        $comments[] = 'ID источника: ' . $announcement->getAvitoId();
        $comments[] = "Координаты: lat: {$announcement->getLat()}, lng: {$announcement->getLng()}";
        $comments[] = 'Адрресс: ' . $announcement->getAddress();
        $comments[] = 'Тип владельца: ' . $announcement->getPersonType();
        $comments[] = 'Источник: ' . $announcement->getSource();
        $comments[] = 'Метро: ' . $announcement->getMetro();
        $comments[] = 'Ссылка на источник: ' . $announcement->getUrl();
        $comments[] = 'Категория1: ' . $announcement->getCat1();
        $comments[] = 'Категория2: ' . $announcement->getCat2();
        $comments[] = 'Тип сделки: ' . $announcement->getNedvigimostType();
        $comments[] = 'Время до метро: ' . $announcement->getKmDoMetro();
        $comments[] = 'Контакт1: ' . $announcement->getContactName();
        $comments[] = 'Контакт 2:' . $announcement->getPerson();
        $comments[] = 'Дата добавления:' . $announcement->getTime();
        $comments[] = 'Заголовок: ' . $announcement->getTitle();
        $comments[] = 'Телефон: ' . $announcement->getPhone();
        $comments[] = 'Город: ' . $announcement->getCity();
        $comments[] = 'Регион: ' . $announcement->getRegion();
        $comments[] = 'Оператор: ' . $announcement->getPhoneOperator();
        $comments[] = 'Регион телефона: ' . $announcement->getPhoneRegion();
        $comments[] = 'Обьявлений  с таким телефоном: ' . $announcement->getCountAdsSamePhone();

        $block->comment = implode(PHP_EOL, $comments);
        if ($block->id) {
            echo 'START DELETE OLD PHOTOS FOR BLOCK ID: ' . $block->id . PHP_EOL;
            $this->photoService->deleteDraftPhotos($block);
            echo 'DONE DELETE OLD PHOTOS FOR BLOCK ID: ' . $block->id . PHP_EOL;
        }
        $block->save();
        echo 'START UPLOAD IMAGES FOR BLOCK ID: ' . $block->id . PHP_EOL;
        echo 'PHOTO SOURCE: ' . $announcement->getSource() . PHP_EOL;
        foreach ($announcement->getImages() as $image) {
            $photo = new BlockPhoto();
            $photo->status = BlockPhoto::STATUS_DRAFT;
            $block->photos()->save($photo);

            $tempFile = '/tmp/temporary_import_image.png';

            file_put_contents($tempFile, file_get_contents($image->getUrl()));

            echo 'PHOTO PATH: ' . $photo->getPhotoName() . PHP_EOL;
            $this->photoService->imageStore($photo, new UploadedFile($tempFile, $image->getFileName()));
            unlink($tempFile);
            $photo->refresh();
        }
        echo 'DONE UPLOAD IMAGES FOR BLOCK ID: ' . $block->id . PHP_EOL;

        $block->load('photos');

        echo 'IMPORT BLOCK COMPLETED: ' . $block->id . PHP_EOL;
        return $block;
    }
}
