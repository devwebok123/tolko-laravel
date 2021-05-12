<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metros', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('line');
            $table->foreignId('region_id')->constrained()->onDelete('restrict');
            $table->unsignedInteger('cian_id')->nullable();
            $table->unique(['name', 'line']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metros');
    }
}
