<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBlockPublicationsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_publications_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->comment('Блок');
            $table->dateTime('stat_date')->comment('День статистики')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedTinyInteger('type')->comment('Тип, поставили на публикацию или сняли с публикации');
            $table->unsignedTinyInteger('cian_promo')->nullable()->comment('Циан тип публикации');
            $table->unsignedSmallInteger('bet')->nullable()->comment('Ставка');
            $table->string('ad_title')->nullable()->comment('Заголовок для циан');
            $table->timestamps();

            $table->foreign('block_id', 'block_publications_history_blocks_id_fkey')
                ->references('id')
                ->on('blocks')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');

            $table->index(['stat_date', 'block_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('block_publications_history');
    }
}
