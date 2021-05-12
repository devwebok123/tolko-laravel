<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBuildingsAlterAddressSegments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->bigInteger('address_region_code')->nullable()->comment('Номер региона');
            $table->string('address_raion')->nullable()->comment('Название города или района');
            $table->string('address_settlement')->nullable()->comment('Населенный пункт');
            $table->string('address_street')->nullable()->comment('Улица');
            $table->string('address_house')->nullable()->comment('Номер дома');
            $table->string('address_building')->nullable()->comment('Строение');
            $table->string('address_block')->nullable()->comment('Корпус');
            $table->integer('address_index')->nullable()->comment('Индекс');
            $table->string('address_address')->nullable()->comment('Адрес');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('address_region_code');
            $table->dropColumn('address_raion');
            $table->dropColumn('address_settlement');
            $table->dropColumn('address_street');
            $table->dropColumn('address_house');
            $table->dropColumn('address_building');
            $table->dropColumn('address_block');
            $table->dropColumn('address_index');
            $table->dropColumn('address_address');
        });
    }
}
