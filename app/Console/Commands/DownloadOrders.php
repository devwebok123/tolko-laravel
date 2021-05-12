<?php

namespace App\Console\Commands;

use App\Services\Models\BlockOrderService;
use Illuminate\Console\Command;

class DownloadOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'block_orders:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download orders after pay';

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
     * @param BlockOrderService $orderService
     * @return int
     */
    public function handle(BlockOrderService $orderService): int
    {
        $orderService->downloadOrders();
        return 0;
    }
}
