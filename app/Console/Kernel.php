<?php

namespace App\Console;

use App\Console\Commands\SendMeetingReminders;
use App\Console\Commands\SendAutomaticReminders;
use App\Console\Commands\SendResponseReminders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // EF42 - Rappels automatiques paramétrables (J-7, J-1, jour J)
        // Rappels J-7 (7 jours avant)
        $schedule->command(SendAutomaticReminders::class, ['--days' => 7])
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Rappels J-1 (1 jour avant)
        $schedule->command(SendAutomaticReminders::class, ['--days' => 1])
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Rappels jour J (le jour même)
        $schedule->command(SendAutomaticReminders::class, ['--days' => 0])
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->runInBackground();

        // EF43 - Relances automatiques pour les participants sans réponse
        $schedule->command(SendResponseReminders::class)
            ->dailyAt('10:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Vérifie toutes les minutes si des rappels doivent être envoyés (ancien système)
        $schedule->command(SendMeetingReminders::class, ['--window' => 2])
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
