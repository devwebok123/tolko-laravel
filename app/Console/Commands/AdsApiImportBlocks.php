<?php

namespace App\Console\Commands;

use App\Services\AdsApi\ImportBlockService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AdsApiImportBlocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads_api:import:blocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import blocks from ads api';


    public function handle(ImportBlockService $service): int
    {
        $service->import(Carbon::now()->subMinutes(10), Carbon::now());

        return 0;
    }
}
