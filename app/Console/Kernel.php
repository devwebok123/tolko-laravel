<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cian:import:notifications')->everyTenMinutes()->runInBackground();
        $schedule->command('cian:import:complaints')->everyTenMinutes()->runInBackground();
        $schedule->command('cian:import:block_statistics')->hourly()->runInBackground();
        $schedule->command('xml:cian')->everyTenMinutes()->runInBackground();
        $schedule->command('xml:avito')->everyTenMinutes()->runInBackground();
        $schedule->command('xml:yandex')->everyTenMinutes()->runInBackground();
        $schedule->command('block_orders:pay')->everyTenMinutes()->runInBackground();
        $schedule->command('block_order:download')->everyTenMinutes()->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
