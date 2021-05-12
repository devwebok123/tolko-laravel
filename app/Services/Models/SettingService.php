<?php


namespace App\Services\Models;

use App\DataObjects\Setting\SettingObject;
use App\Http\Requests\Setting\UpdateRequest;
use App\Models\Setting;

class SettingService
{

    /**
     * @return SettingObject
     * @throws \Exception
     */
    public function getObject(): SettingObject
    {
        return SettingObject::createFromCollection(Setting::all());
    }

    /**
     * @param UpdateRequest $request
     * @return SettingObject
     * @throws \Exception
     */
    public function update(UpdateRequest $request): SettingObject
    {
        $this->updateSetting(Setting::NAME_PHONE_CIAN, $request->getPhoneCian());
        $this->updateSetting(Setting::NAME_PHONE_AVITO, $request->getPhoneAvito());
        $this->updateSetting(Setting::NAME_PHONE_YANDEX, $request->getPhoneYandex());

        return $this->getObject();
    }

    /**
     * @param string $name
     * @return Setting
     */
    public function getByName(string $name): Setting
    {
        return Setting::where('name', $name)->first();
    }

    /**
     * @param string $name
     * @param string $newValue
     * @return Setting
     */
    public function updateSetting(string $name, string $newValue): Setting
    {
        $setting = $this->getByName($name);
        $setting->value = $newValue;
        $setting->save();

        return $setting;
    }
}
