<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBuildingsAddExtraFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('address_raion');
            $table->string('address_region')->nullable()->comment('Регион')->after('address_region_code');
            $table->string('address_city')->nullable()->comment('Город')->after('address_region');
            $table->timestamp('addressed_at')->nullable()->comment('Дата обновления')->after('address_address');
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
            $table->string('address_raion')->nullable()->comment('Название города или района');
            $table->dropColumn('address_region');
            $table->dropColumn('address_city');
            $table->dropColumn('addressed_at');
        });
    }
}
