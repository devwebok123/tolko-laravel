<?php

namespace App\Console\Commands;

use App\Services\Cian\CianService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class CianImportBlockMissedStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cian:import:block_missed_statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import block missed statistics from cian';

    /**
     * Execute the console command.
     *
     * @param CianService $service
     * @return int
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function handle(CianService $service): int
    {
        $day = new Carbon('2020-10-30');
        while ($day->getTimestamp() < time()) {
            $this->output->writeln('DAY: ' . $day->format('Y-m-d'));
            $service->importBlocks($day);
            $day->addDay();
        }

        return 0;
    }
}
