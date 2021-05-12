<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\NotificationCollection;
use App\Http\Resources\Notification\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return new NotificationCollection(Notification::query()
            ->orderBy('is_resolved', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(
                $request->get('per_page'),
                '*',
                'page',
                $request->get('page')
            ));
    }

    public function resolve(Notification $notification)
    {
        $notification->is_resolved = 1;
        $notification->save();

        return NotificationResource::make($notification);
    }
}
