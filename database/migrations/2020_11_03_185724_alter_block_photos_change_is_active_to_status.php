<?php

use App\Models\BlockPhoto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBlockPhotosChangeIsActiveToStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('block_photos', function (Blueprint $table) {
            BlockPhoto::where('is_active', 0)
                ->update(['is_active' => \App\Models\BlockPhoto::STATUS_NOT_ACTIVE]);
            $table->renameColumn('is_active', 'status');
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
            BlockPhoto::where('status', BlockPhoto::STATUS_NOT_ACTIVE)
                ->update(['status' => 0]);
            $table->renameColumn('status', 'is_active');
        });
    }
}
