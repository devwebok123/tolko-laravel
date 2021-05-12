<?php

namespace App\Console\Commands;

use App\Services\Cian\CianService;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class CianImportBlockStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cian:import:block_statistics {day? : Date for import statistic, format- 2020-10-26}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import block statistics from cian';

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
        $day = $this->input->getArgument('day');
        if ($day) {
            try {
                $day = Carbon::createFromFormat('Y-m-d', $day);
            } catch (InvalidFormatException $e) {
                $this->output->writeln("<error>Invalid date format {$day}, correct Y-m-d, example: 2020-10-26</error>");
                return 0;
            }
        } else {
            $day = Carbon::yesterday();
        }
        $service->importBlocks($day);

        return 0;
    }
}
