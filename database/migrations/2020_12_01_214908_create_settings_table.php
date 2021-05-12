<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        Setting::query()->insert([
            [
                'name' => Setting::NAME_PHONE_CIAN,
                'value' => '+74952781841',
            ],
            [
                'name' => Setting::NAME_PHONE_AVITO,
                'value' => '+74996474259',
            ],
            [
                'name' => Setting::NAME_PHONE_YANDEX,
                'value' => '+74952781841',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
