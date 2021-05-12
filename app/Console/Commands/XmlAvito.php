<?php

namespace App\Console\Commands;

use App\Jobs\AvitoFeedGen;
use Illuminate\Console\Command;

class XmlAvito extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xml:avito';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Avito feed generate';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        AvitoFeedGen::dispatch();
        return 0;
    }
}
