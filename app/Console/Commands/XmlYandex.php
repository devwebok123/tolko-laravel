<?php

namespace App\Console\Commands;

use App\Jobs\YandexFeedGen;
use Illuminate\Console\Command;

class XmlYandex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xml:yandex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Yandex feed generate';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        YandexFeedGen::dispatch();
        return 0;
    }
}
