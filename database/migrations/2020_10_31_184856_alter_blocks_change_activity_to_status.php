<?php

use App\Models\Block;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBlocksChangeActivityToStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blocks', function (Blueprint $table) {
            Block::whereActivity(0)->update(['activity' => Block::STATUS_NOT_ACTIVE]);
            $table->renameColumn('activity', 'status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blocks', function (Blueprint $table) {
            Block::whereStatus(Block::STATUS_NOT_ACTIVE)->update(['status' => 0]);
            $table->renameColumn('status', 'activity');
        });
    }
}
