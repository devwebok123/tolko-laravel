<?php

namespace App\Console\Commands;

use App\Exceptions\NotEnoughMoneyException;
use App\Exceptions\PayTransactionErrorException;
use App\Services\Models\BlockOrderService;
use Illuminate\Console\Command;

class PayOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'block_orders:pay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pay new block orders';

    /**
     * Execute the console command.
     *
     * @param BlockOrderService $orderService
     * @return int
     * @throws NotEnoughMoneyException
     * @throws PayTransactionErrorException
     */
    public function handle(BlockOrderService $orderService): int
    {
        $orderService->payOrders();
        return 0;
    }
}
