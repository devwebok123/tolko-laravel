<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained()->onDelete('restrict');
            $table->foreignId('contact_id')->constrained()->onDelete('restrict');
            $table->unsignedTinyInteger('floor');
            $table->unsignedSmallInteger('flat_number')->nullable();
            $table->unsignedDecimal('area', 5, 2);
            $table->unsignedDecimal('living_area', 5, 2)->nullable();
            $table->unsignedDecimal('kitchen_area', 4, 2)->nullable();
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('rooms');
            $table->unsignedTinyInteger('rooms_type')->default(1);
            $table->unsignedTinyInteger('balcony');
            $table->unsignedTinyInteger('windowsInOut')->default(1);
            $table->unsignedTinyInteger('separate_wc_count')->nullable();
            $table->unsignedTinyInteger('combined_wc_count')->nullable();
            $table->unsignedTinyInteger('renovation')->default(2);
            $table->set('filling', [1,2,3,4,5,6,7,8,9])->nullable();
            $table->set('shower_bath', [1,2])->nullable();
            $table->set('living_conds', [1,2,3,4,5,6])->nullable();
            $table->unsignedTinyInteger('tenant_count_limit')->nullable();
            $table->string('cadastral_number')->nullable();
            $table->string('description', 4095)->nullable();
            $table->string('comment', 1023)->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('activity')->default(1);
            $table->boolean('out_of_market')->default(1);
            $table->unsignedTinyInteger('currency')->default(1);
            $table->boolean('contract_signed');
            $table->unsignedDecimal('commission', 5, 2)->default(0);
            $table->unsignedTinyInteger('commission_type')->default(1);
            $table->string('commission_comment', 4095)->nullable();
            $table->set('included', [1,2,3])->default(1);
            $table->unsignedDecimal('parking_cost', 7, 2)->nullable();
            $table->unsignedDecimal('cost', 10, 2);
            $table->unsignedDecimal('bargain', 10, 2)->nullable();
            $table->unsignedTinyInteger('cian')->nullable();
            $table->unsignedSmallInteger('bet')->nullable();
            $table->string('ad_title')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('blocks');
    }
}
