<?php


namespace App\Providers;

use App\Models\Block;
use App\Observers\BlockObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Boot entity observers
     */
    public function boot()
    {
        Block::observe(BlockObserver::class);
    }
}
