<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockPublicationsStatisticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_publications_statistic', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('source');
            $table->foreignId('block_id')->comment('Блок');
            $table->date('stat_date');
            $table->unsignedInteger('coverage');
            $table->unsignedInteger('shows_count');
            $table->unsignedInteger('searches_count');
            $table->unsignedInteger('phones_shows');
            $table->timestamps();

            $table->foreign('block_id', 'block_publications_statistic_blocks_id_fkey')
                ->references('id')
                ->on('blocks')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');

            $table->unique(['stat_date', 'block_id', 'source']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('block_publications_statistic');
    }
}
