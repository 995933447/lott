<?php

namespace App\Console;

use App\Console\Commands\Test2Command;
use App\Console\Commands\TestCommand;
use App\Console\Commands\UsableLotteriesIssuesGenerator;
use App\Console\Commands\UsableLotteriesIssuesDrawer;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        UsableLotteriesIssuesDrawer::class,
        UsableLotteriesIssuesGenerator::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(UsableLotteriesIssuesGenerator::SIGNATURE)->daily();
        $schedule->command(UsableLotteriesIssuesDrawer::SIGNATURE)->everyMinute();
        $schedule->command('queue:retry all')->everyFiveMinutes();
    }
}
