<?php

namespace App\Console\Commands;

use App\Services\Cian\CianService;
use Illuminate\Console\Command;

class CianImportComplaints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cian:import:complaints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import cian complaints';

    /**
     * @param CianService $service
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function handle(CianService $service): int
    {
        $service->importComplaints();

        return 0;
    }
}
