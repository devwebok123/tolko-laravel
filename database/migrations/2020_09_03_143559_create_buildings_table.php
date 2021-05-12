<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('name')->nullable();
            $table->foreignId('region_id')->constrained()->onDelete('restrict');
            $table->foreignId('metro_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('metro_id_2')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('metro_id_3')->nullable()->constrained()->onDelete('restrict');
            $table->unsignedTinyInteger('metro_time')->nullable();
            $table->unsignedTinyInteger('metro_time_type')->nullable();
            $table->unsignedSmallInteger('metro_distance')->nullable();
            $table->unsignedTinyInteger('mkad_distance')->nullable();
            $table->unsignedSmallInteger('year_construction')->nullable();
            $table->unsignedTinyInteger('type')->nullable();
            $table->string('series')->nullable();
            $table->unsignedDecimal('ceil_height', 4, 2)->nullable();
            $table->unsignedTinyInteger('passenger_lift_count')->nullable();
            $table->unsignedTinyInteger('cargo_lift_count')->nullable();
            $table->boolean('garbage_chute')->nullable();
            $table->enum('class', ["A","B","C","D"])->nullable();
            $table->unsignedTinyInteger('floors');
            $table->unsignedTinyInteger('parking_type')->nullable();
            $table->boolean('near_infra')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
