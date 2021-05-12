<?php

namespace App\Console\Commands;

use App\Jobs\FeedGen;
use Illuminate\Console\Command;

class Xml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xml:cian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cian feed generate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        Service::cian();
        FeedGen::dispatch();
        return 0;
    }
}
