<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('block_id')->comment('Блок');
            $table->unsignedInteger('tag_id')->nullable()->comment('Тег');
            $table->string('name')->nullable()->comment('Название');
            $table->smallInteger('rank')->default(0)->comment('Сортировка');
            $table->boolean('is_active')->default(1)->comment('Активность');

            $table->foreign('block_id', 'block_photos_blocks_id_fkey')
                ->references('id')
                ->on('blocks')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('block_photos', function (Blueprint $table) {
            $table->dropForeign('block_photos_blocks_id_fkey');
        });
        Schema::dropIfExists('block_photos');
    }
}
