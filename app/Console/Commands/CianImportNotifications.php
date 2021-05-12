<?php

namespace App\Console\Commands;

use App\Services\Cian\CianService;
use Illuminate\Console\Command;

class CianImportNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cian:import:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import cian notifications';


    public function handle(CianService $service): int
    {
        $service->importNotifications();

        return 0;
    }
}
