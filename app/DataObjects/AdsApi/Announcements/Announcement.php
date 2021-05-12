<?php


namespace App\DataObjects\AdsApi\Announcements;

use App\DataObjects\BaseObject;
use Carbon\Carbon;

class Announcement extends BaseObject
{
    /**
     * ID объявления на сайте-источнике
     * @var int $avitoId
     */
    protected $avitoId;
    /**
     * Координаты
     * @var string $lat
     */
    protected $lat;
    /**
     * Координаты
     * @var string $lng
     */
    protected $lng;
    /**
     * Город
     * @var string $city
     */
    protected $city;
    /**
     * Тип владельца(частное, агенство, частное(фильтр)
     * @var string $personType
     */
    protected $personType;
    /**
     * ID типа персоны для контактов. 1 - "Частное лицо", 2 - "Агентство" или 3 - "Частное лицо (фильтр)"
     * @var int $personTypeId
     */
    protected $personTypeId;
    /**
     * Источник
     * @var string $source
     */
    protected $source;
    /**
     * ID cайта-источника в нашей системе
     * @var int $sourceId
     */
    protected $sourceId;
    /**
     * Метро
     * @var string $metro
     */
    protected $metro;
    /**
     * Ссылка на обьявление с источника
     * @var string $url
     */
    protected $url;
    /**
     * ID категории первого уровня, например, категория Недвижимость имеет значение 1
     * @var int $cat1Id
     */
    protected $cat1Id;
    /**
     * Название категории первого уровня, например, Недвижимость
     * @var string $cat1
     */
    protected $cat1;
    /**
     * ID категории второго уровня, например, категория Квартиры имеет значение 2
     * @var int $cat2Id
     */
    protected $cat2Id;
    /**
     * Название категории второго уровня, например, Квартиры
     * @var string $cat2
     */
    protected $cat2;
    /**
     * Описание
     * @var string $description
     */
    protected $description;
    /**
     * Тип недвижимости: Продам, Сдам, Куплю или Сниму
     * @var string $nedvigimostType
     */
    protected $nedvigimostType;
    /**
     * ID типа недвижимости: 1 - Продам, 2 - Сдам, 3 - Куплю или 4 - Сниму
     * @var int $nedvigimostTypeId
     */
    protected $nedvigimostTypeId;
    /**
     * Цена
     * @var int $price
     */
    protected $price;
    /**
     * Контакт
     * @var string $contactName
     */
    protected $contactName;
    /**
     * Защищен ли телефон
     * 1 - телефон защищен, 0 - не защищен, null - параметр недоступен.
     * @var int $phoneProtected
     */
    protected $phoneProtected;
    /**
     * Идентификатор записи
     * @var int $id
     */
    protected $id;
    /**
     * Расстояние до ближайшего метро в километрах. Если 0, то расстояние неизвестно.
     * @var float $kmDoMetro
     */
    protected $kmDoMetro;
    /**
     * Персона для контактов, автор объявления
     * @var string $person
     */
    protected $person;
    /**
     * Адрес
     * @var string $address
     */
    protected $address;
    /**
     * Дата и время добавления объявления в нашу систему, либо время обновления
     * @var Carbon $time
     */
    protected $time;
    /**
     * Заголовок
     * @var string $title
     */
    protected $title;
    /**
     * Изображения
     * @var Image[] $images
     */
    protected $images = [];
    /**
     * Телефон
     * @var string $phone
     */
    protected $phone;
    /**
     * Только название региона
     * @var string $region
     */
    protected $region;
    /**
     * Только название города
     * @var string $city1
     */
    protected $city1;
    /**
     * Название мобильного оператора
     * @var string $phoneOperator
     */
    protected $phoneOperator;
    /**
     * Регион мобильного телефона
     * @var string $phoneRegion
     */
    protected $phoneRegion;
    /**
     * Количество объявлений с тем же номером.
     * @var int $countAdsSamePhone
     */
    protected $countAdsSamePhone;
    /**
     * Дополнительные параметры
     * @var Params $params
     */
    protected $params;

    protected function __construct(array $data)
    {
        parent::__construct($data);

        $this->avitoId = $data['avitoid'];
        $this->lat = $data['coords']['lat'];
        $this->lng = $data['coords']['lng'];
        $this->city = $data['city'];
        $this->personType = $data['person_type'];
        $this->source = $data['source'];
        $this->metro = $data['metro'];
        $this->url = $data['url'];
        $this->cat1Id = $data['cat1_id'];
        $this->description = $data['description'];
        $this->nedvigimostType = $data['nedvigimost_type'];
        $this->price = $data['price'];
        $this->cat2 = $data['cat2'];

        $this->contactName = $data['contactname'];
        $this->phoneProtected = $data['phone_protected'];
        $this->cat1 = $data['cat1'];
        $this->id = $data['id'];
        $this->kmDoMetro = $data['km_do_metro'];
        $this->person = $data['person'];
        $this->address = $data['address'];
        $this->cat2Id = $data['cat2_id'];
        $this->time = Carbon::createFromFormat('Y-m-d H:i:s', $data['time']);
        $this->title = $data['title'];
        foreach ($data['images'] as $image) {
            $this->images[] = Image::getInstance($image['imgurl']);
        }
        $this->phone = $data['phone'];
        $this->personTypeId = $data['person_type_id'];
        $this->nedvigimostTypeIdd = $data['nedvigimost_type_id'];
        $this->sourceId = $data['source_id'];
        $this->region = $data['region'];
        $this->city1 = $data['city1'];
        $this->phoneOperator = $data['phone_operator'];
        $this->phoneRegion = $data['phone_region'];
        $this->countAdsSamePhone = $data['count_ads_same_phone'];

        $this->params = Params::createFromArray($data['params']);
    }

    /**
     * @return int
     */
    public function getAvitoId(): int
    {
        return $this->avitoId;
    }

    /**
     * @return string
     */
    public function getLat(): string
    {
        return $this->lat;
    }

    /**
     * @return string
     */
    public function getLng(): string
    {
        return $this->lng;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPersonType(): string
    {
        return $this->personType;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getMetro(): string
    {
        return $this->metro;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getCat1Id(): int
    {
        return $this->cat1Id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getNedvigimostType(): string
    {
        return $this->nedvigimostType;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCat2(): string
    {
        return $this->cat2;
    }

    /**
     * @return string
     */
    public function getContactName(): string
    {
        return $this->contactName;
    }

    /**
     * @return int
     */
    public function getPhoneProtected(): int
    {
        return $this->phoneProtected;
    }

    /**
     * @return string
     */
    public function getCat1(): string
    {
        return $this->cat1;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getKmDoMetro(): float
    {
        return $this->kmDoMetro;
    }

    /**
     * @return string
     */
    public function getPerson(): string
    {
        return $this->person;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }


    /**
     * @return int
     */
    public function getCat2Id(): int
    {
        return $this->cat2Id;
    }

    /**
     * @return Carbon
     */
    public function getTime(): Carbon
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Image[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return int
     */
    public function getPersonTypeId(): int
    {
        return $this->personTypeId;
    }

    /**
     * @return int
     */
    public function getNedvigimostTypeId(): int
    {
        return $this->nedvigimostTypeId;
    }

    /**
     * @return int
     */
    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getCity1(): string
    {
        return $this->city1;
    }

    /**
     * @return string
     */
    public function getPhoneOperator(): string
    {
        return $this->phoneOperator;
    }

    /**
     * @return string
     */
    public function getPhoneRegion(): string
    {
        return $this->phoneRegion;
    }

    /**
     * @return int
     */
    public function getCountAdsSamePhone(): int
    {
        return (int)$this->countAdsSamePhone;
    }

    /**
     * @return Params
     */
    public function getParams(): Params
    {
        return $this->params;
    }
}
