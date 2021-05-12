<?php


namespace App\Services\Xml;

use App\DataObjects\Setting\SettingObject;
use App\Services\Models\NotificationService;
use App\Services\Models\SettingService;

class BaseXmlService
{
    /** @var SettingObject $settings */
    protected $settings;


    /** @var SettingService $settingsService */
    protected $settingsService;
    protected $notificationsService;

    public function __construct(SettingService $service, NotificationService $notificationService)
    {
        $this->settingsService = $service;
        $this->notificationsService = $notificationService;
        $this->settings = $service->getObject();
    }
}
