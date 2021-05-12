<?php

namespace App\Jobs;

use App\Services\Xml\Service as FeedService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FeedGen implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @param FeedService $service
     * @return void
     */
    public function handle(FeedService $service)
    {
        $service->cian();
    }
}
