<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    const STATUS_CREATED = 'created';
    const STATUS_UPDATED = 'updated';
    const STATUS_DELETED = 'deleted';
    const SUCCESS_STATUSES = [
        self::STATUS_CREATED,
        self::STATUS_UPDATED,
        self::STATUS_DELETED,
    ];

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
