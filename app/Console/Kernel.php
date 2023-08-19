<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('demo:cron')
        //     ->everyMinute();
        $schedule->command('demo:cron')
            ->dailyAt('13:45');
        // $schedule->command('cron:job')
        //     ->dailyAt('13:30');
        // $schedule->command('send-email:class-booking')
        //     ->dailyAt('19:00');
        $schedule->command('cron:job')
            ->dailyAt('19:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
