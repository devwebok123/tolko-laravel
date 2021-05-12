<?php

namespace App\Console\Commands;

use App\Models\Building;
use App\Models\Line;
use App\Models\Metro;
use App\Models\Region;
use App\Services\DadataService;
use Illuminate\Console\Command;

class AddressParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'address:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @throws \App\Exceptions\DadataException
     */
    public function handle()
    {
        // Init DaDaTa Helper
        $dadataService = new DadataService;

        // Get Building list
        $buidings = Building::query()
            ->select('id', 'address')
            ->whereNull('addressed_at')
            ->orderBy('id', 'asc')
            ->limit(10000)
            ->get();

        //
        foreach ($buidings as $building) {
            print $building->id;

            // Mutate address
            // Get adress info
            $addresses = $dadataService->getAddressSuggest($building->address);

            if ($addresses) {
                $parsedAddress = $addresses [0];
                $addressSegments = DadataService::parseAddress($parsedAddress);

                // Update model
                $building->address_region_code = $addressSegments[0]['region_kladr_id'];
                $building->address_region = $addressSegments[0]['region'];
                $building->address_city = $addressSegments[0]['city'];
                $building->address_settlement = $addressSegments[0]['settlement'];
                $building->address_street = $addressSegments[0]['street'];
                $building->address_house = $addressSegments[0]['house'];
                // Block OR Building
                if ($addressSegments[0]['block_type'] == 'к') {
                    try {
                        // Check if Block together with building
                        if (preg_match('/^(\S+) стр (\S+)?/', $addressSegments[0]['block'], $matches)) {
                            $building->address_block = $matches [1];
                            $building->address_building = $matches [2];
                        } else {
                            $building->address_block = $addressSegments[0]['block'];
                            $building->address_building = null;
                        }
                    } catch (\Exception $e) {
                        echo $parsedAddress."\n";
                        echo $e->getMessage()."\n";
                        continue;
                    }
                } else {
                    $building->address_block = null;
                    $building->address_building = $addressSegments[0]['block'];
                }
                $building->address_index = $addressSegments[0]['postal_code'];
                $building->address_address = $parsedAddress;

                $regionModel = Region::where('name', $addressSegments[0]['city_district'])->first();

                // Metro
                if (isset($addressSegments[0]['metro'])) {
                    // Metro Distance in meters
                    $building->metro_distance = $addressSegments[0]['metro'][0]['distance']*1000;
                    $building->metro_time_type = $building->metro_distance > 2500 ? 2 : 1;
                    $building->metro_time = $building->metro_distance / ($building->metro_time_type == 1 ? 100 : 333);

                    $metroIterator = 1;
                    foreach ($addressSegments[0]['metro'] as $station) {
                        // Find Line or Add
                        $lineModel = Line::where('name', $station ['line'])->first();
                        if (!$lineModel) {
                            $lineModel = new Line;
                            $lineModel->name = $station ['line'];
                            $lineModel->save();

                            $this->info('Added new Metro Line: '.$lineModel->name."\n");
                        }

                        // Find Station or Add
                        $metroModel = Metro::query()
                            ->where('name', $station['name'])
                            ->where('line', $lineModel->id)
                            ->first();
                        if (!$metroModel) {
                            // $regionId
                            $regionId = $regionModel->id;

                            // $cianId
                            $cianId = null;

                            // Add Station
                            $metroModel = new Metro;
                            $metroModel->name = $station['name'];
                            $metroModel->line = $lineModel->id;
                            $metroModel->region_id = $regionId;
                            $metroModel->cian_id = $cianId;
                            $metroModel->save();

                            $this->line('Added new Metro Station: '.$metroModel->name."\n");
                        }

                        if ($metroIterator == 1) {
                            $building->metro_id = $metroModel->id;
                        }
                        if ($metroIterator == 2) {
                            $building->metro_id_2 = $metroModel->id;
                        }
                        if ($metroIterator == 3) {
                            $building->metro_id_3 = $metroModel->id;
                        }

                        $metroIterator++;
                    }
                }

                $building->addressed_at = \date('Y-m-d H:i:s');
                $building->save();

                if (!empty($building->address_address)) {
                    print ' - done ('.$building->address.' => '.$parsedAddress.')'."\n";
                } else {
                    print ' - parsed address is empty ('.$building->address.' => '.$parsedAddress.')'."\n";
                }
            } else {
                print ' - response is empty ('.$building->address.')'."\n";
            }
        }

        return 0;
    }
}
