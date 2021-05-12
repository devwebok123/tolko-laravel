<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedAvitoPromoToBlockPublicationsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('block_publications_history', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id');
            $table->foreign('user_id', 'block_publications_statistic_user_id_fkey')
                ->references('id')
                ->on('users')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');

            $table->unsignedTinyInteger('avito_promo')->nullable()->after('ad_title');
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
            $table->dropForeign('block_publications_statistic_user_id_fkey');
            $table->dropColumn('user_id');
            $table->dropColumn('avito_promo');
        });
    }
}
