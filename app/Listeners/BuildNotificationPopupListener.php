<?php


namespace App\Listeners;

use App\Models\Notification;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class BuildNotificationPopupListener
{

    public function handle(BuildingMenu $event)
    {
        $notifications = Notification::query()
            ->where('is_resolved', 0)
            ->limit(10)
            ->get();

        $submenu = [];

        /** @var Notification $notification */
        foreach ($notifications as $notification) {
            $submenu[] = [
                'text' => mb_substr($notification->text, 0, 30),
                'url' => '/notifications'
            ];
        }

        $event->menu->add(
            [
                'key' => 'notifications',
                'text' => '',
                'icon' => 'fas fa-fw fa-bell',
                'topnav_right' => true,
                'label' => Notification::whereIsResolved(0)->count(),
                'submenu' => $submenu
            ],
        );
    }
}
