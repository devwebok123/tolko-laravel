<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\Api\BlockController as ApiBlockController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('block/drafts', [BlockController::class, 'drafts']);
    Route::resources([
        'building' => BuildingController::class,
        'block' => BlockController::class,
    ]);
    Route::get('/setting', [SettingController::class, 'edit'])->name('setting.edit');
    Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');

    Route::post('search', [ApiBlockController::class, 'search'])->name('block.search');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index.view');
});
