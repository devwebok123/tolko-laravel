<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBlocksAddedCianOfferId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blocks', static function (Blueprint $table) {
            $table->unsignedInteger('cian_offer_id')->nullable()->index()->after('cian_publication_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('blocks', static function (Blueprint $table) {
            $table->dropColumn('cian_offer_id');
        });
    }
}
