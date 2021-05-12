<?php

use App\Models\BlockOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id');
            $table->foreignId('user_id')->nullable();
            $table->string('type', 8);
            $table->unsignedTinyInteger('status')->default(BlockOrder::STATUS_NEW);
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('document_id');
            $table->string('path', 512)->nullable();
            $table->timestamp('pay_date')->nullable();
            $table->timestamps();

            $table->unique(['block_id', 'type']);

            $table->foreign('block_id', 'block_orders_blocks_id_fkey')
                ->references('id')
                ->on('blocks')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');

            $table->foreign('user_id', 'block_orders_users_id_fkey')
                ->references('id')
                ->on('users')
                ->onUpdate('RESTRICT')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('block_orders');
    }
}
