<?php


namespace App\DataObjects\Setting;

use App\DataObjects\BaseObject;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingObject extends BaseObject
{
    /** @var string|null $phoneCian */
    protected $phoneCian;
    /** @var string|null $phoneAvito */
    protected $phoneAvito;
    /** @var string|null $phoneYandex */
    protected $phoneYandex;


    /**
     * SettingObject constructor.
     * @param array $data
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
        foreach ($data as $setting) {
            switch ($setting['name']) {
                case Setting::NAME_PHONE_CIAN:
                    $this->phoneCian = $setting['value'];
                    break;
                case Setting::NAME_PHONE_AVITO:
                    $this->phoneAvito = $setting['value'];
                    break;
                case Setting::NAME_PHONE_YANDEX:
                    $this->phoneYandex = $setting['value'];
                    break;
                default:
                    throw new \Exception("Unprocessable setting: " . $setting['name']);
            }
        }
    }

    /**
     * @param Collection|Setting[] $collection
     * @return self
     * @throws \Exception
     */
    public static function createFromCollection(Collection $collection): self
    {
        return new self($collection->toArray());
    }

    /**
     * @return string|null
     */
    public function getPhoneCian(): ?string
    {
        return $this->phoneCian;
    }

    /**
     * @return string|null
     */
    public function getPhoneAvito(): ?string
    {
        return $this->phoneAvito;
    }

    /**
     * @return string|null
     */
    public function getPhoneYandex(): ?string
    {
        return $this->phoneYandex;
    }
}
