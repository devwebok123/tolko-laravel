<?php

use App\Http\Controllers\Api\BlockController;
use App\Http\Controllers\Api\BlockPhotoController;
use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\MetroController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\ApiRosReestrController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth']], function () {
    Route::apiResources([
        'buildings' => BuildingController::class,
        'blocks' => BlockController::class,
    ]);

    /* mass actions */
    Route::post('block/mass', [BlockController::class, 'mass'])->name('block.mass');
    Route::delete('block/mass-destroy', [BlockController::class, 'massDestroy'])->name('block.mass-destroy');
    Route::delete('building/mass-destroy', [BuildingController::class, 'massDestroy'])
        ->name('building.mass-destroy');

    /* photos */
    Route::post('block/{block}/photo', [BlockPhotoController::class, 'upload'])->name('block.photo.upload');
    Route::put('block/{block}/photo/update', [BlockPhotoController::class, 'update'])->name('block.photo.update');
    Route::put('block/{block}/photo/sort', [BlockPhotoController::class, 'sort'])->name('block.photo.sort');
    Route::delete('block/{block}/photo/destroy', [BlockPhotoController::class, 'destroy'])
        ->name('block.photo.destroy');
    Route::get('block/{block}/addt-row-info', [BlockController::class, 'addtRowInfo']);
    Route::get('block/{block}/deactivate', [BlockController::class, 'deactivate']);
    Route::get('block/{block}/activate', [BlockController::class, 'activate']);
    Route::get('block/{block}/draft', [BlockController::class, 'draft']);

    /* ApiRosReestr */
    Route::post('block/{block}/apirosreestr/search', [ApiRosReestrController::class, 'search'])
        ->name('block.apirosreestr.search');
    Route::post('block/{block}/apirosreestr/objectinfofull', [ApiRosReestrController::class, 'objectInfoFull'])
        ->name('block.apirosreestr.objectinfofull');
    Route::post('block/{block}/apirosreestr/cadastral', [ApiRosReestrController::class, 'cadastral'])
        ->name('block.apirosreestr.cadastral');
    Route::post('block/{block}/apirosreestr/saveorder', [ApiRosReestrController::class, 'saveOrder'])
        ->name('block.apirosreestr.saveorder');

    /* daData */
    Route::post('building/address-suggest', [BuildingController::class, 'addressSuggest'])
        ->name('building.address-suggest');
    Route::post('building/address-info', [BuildingController::class, 'addressInfo'])
        ->name('building.address-info');

    /* autocompletes */
    Route::post('block/autocomplete/id', [BlockController::class, 'getBlockIds'])->name('block.autocomplete.id');
    Route::post('building/autocomplete/address', [BuildingController::class, 'getBuildingAddress'])
        ->name('building.autocomplete.address');
    Route::post('building/autocomplete/name', [BuildingController::class, 'getBuildingName'])
        ->name('building.autocomplete.name');
    Route::post('region/autocomplete/name', [RegionController::class, 'getRegionName'])
        ->name('region.autocomplete.name');
    Route::post('metro/autocomplete/name', [MetroController::class, 'getMetroName'])
        ->name('metro.autocomplete.name');
    Route::post('contact/autocomplete/name', [ContactController::class, 'getContactName'])
        ->name('contact.autocomplete.name');

    /* NOTIFICATIONS */
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index.api');
    Route::put('notifications/{notification}/resolve', [NotificationController::class, 'resolve'])
        ->name('notifications.resolve.api');
});

/* CLAIMS */
Route::post('/claim', [ClaimController::class, 'store']);
