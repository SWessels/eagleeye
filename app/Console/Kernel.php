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
        // Commands\Inspire::class,
        Commands\ImportCron::class,
        Commands\ImportCoupons::class,
        Commands\SendTryoutEmails::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //$schedule->call('App\Http\Controllers\ProductAvg@index')->twiceDaily(0, 12);
        //$schedule->call('App\Http\Controllers\Woolink@index')->twiceDaily(0, 12);
        //$schedule->call('App\Http\Controllers\Woolink@users')->Daily();

        // import orders
        $schedule->command('emails:send')->Daily();
        $schedule->command('import:orders')->everyTenMinutes()->appendOutputTo('import_orders_report.txt');
        $schedule->command('import:coupons')->everyTenMinutes()->appendOutputTo('import_coupons_report.txt');
    }
}
