<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // Register your custom commands here
        \App\Console\Commands\RetryFailedContactEmails::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Schedule your tasks here
        $schedule->command('contact:retry-emails')->everyFiveMinutes();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
