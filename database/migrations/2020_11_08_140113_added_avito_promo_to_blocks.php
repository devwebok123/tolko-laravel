<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedAvitoPromoToBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->unsignedTinyInteger('avito_promo')->nullable()->after('cian_offer_id');
            $table->dateTime('avito_publication_date')->nullable()->after('avito_promo');
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
            $table->dropColumn('avito_promo');
            $table->dropColumn('avito_publication_date');
        });
    }
}
