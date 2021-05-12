<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterBlockAddedNullableValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->foreignId('contact_id')->nullable()->change();
            DB::statement('ALTER TABLE `blocks` CHANGE `floor` `floor` TINYINT(3) UNSIGNED NULL;');
            DB::statement('ALTER TABLE `blocks` CHANGE `area` `area` DECIMAL(5,2) UNSIGNED NULL;');
            DB::statement('ALTER TABLE `blocks` CHANGE `rooms` `rooms` TINYINT(3) UNSIGNED NULL DEFAULT 0;');
            DB::statement("ALTER TABLE `blocks` CHANGE `balcony` `balcony` TINYINT(3) UNSIGNED NULL DEFAULT '0';");
            DB::statement("ALTER TABLE `blocks` CHANGE `windowsInOut` `windowsInOut` TINYINT(3) UNSIGNED NULL DEFAULT '1';");
            DB::statement("ALTER TABLE `blocks` CHANGE `renovation` `renovation` TINYINT(3) UNSIGNED NULL DEFAULT '2';");
            DB::statement("ALTER TABLE `blocks` CHANGE `cost` `cost` DECIMAL(10,2) UNSIGNED NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->foreignId('contact_id')->nullable(false)->change();
            DB::statement('ALTER TABLE `blocks` CHANGE `floor` `floor` TINYINT(3) UNSIGNED NOT NULL;');
            DB::statement('ALTER TABLE `blocks` CHANGE `area` `area` DECIMAL(5,2) UNSIGNED NOT NULL;');
            DB::statement("ALTER TABLE `blocks` CHANGE `rooms` `rooms` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0';");
            DB::statement("ALTER TABLE `blocks` CHANGE `balcony` `balcony` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0';");
            DB::statement("ALTER TABLE `blocks` CHANGE `windowsInOut` `windowsInOut` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1';");
            DB::statement("ALTER TABLE `blocks` CHANGE `renovation` `renovation` TINYINT(3) UNSIGNED NOT NULL DEFAULT '2';");
            DB::statement("ALTER TABLE `blocks` CHANGE `cost` `cost` DECIMAL(10,2) UNSIGNED NOT NULL;");

        });
    }
}
