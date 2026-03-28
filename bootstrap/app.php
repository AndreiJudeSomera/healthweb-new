<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        // Runs every 5 minutes. The command guards against duplicates via reminder_*_sent_at columns.
        // 2-day reminders fire once per eligible appointment; 30-min window is 28-32 min ahead.
        $schedule->command('appointments:send-reminders')->everyFiveMinutes();
    })
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->alias([
      
        'role'           => \App\Http\Middleware\RoleMiddleware::class,
        'patient.record' => \App\Http\Middleware\RequirePatientRecord::class,
    ]);
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
