<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedYandexPromoToBlockPublicationsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('block_publications_history', function (Blueprint $table) {
            $table->unsignedTinyInteger('yandex_promo')->after('avito_promo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('block_publications_history', function (Blueprint $table) {
            $table->dropColumn('yandex_promo');
        });
    }
}
