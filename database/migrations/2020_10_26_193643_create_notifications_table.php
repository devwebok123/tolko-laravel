<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('source');
            $table->unsignedInteger('external_id');
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('is_resolved');
            $table->text('text');
            $table->unsignedInteger('offer_id')->nullable();
            $table->dateTime('notification_date');
            $table->timestamps();

            $table->unique(['external_id', 'source', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
