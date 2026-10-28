<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes & Scheduler - Website Uptime Monitor
|--------------------------------------------------------------------------
*/

// Dispatch due monitoring checks every minute without overlapping
Schedule::command('uptime:run-due-checks')->everyMinute()->withoutOverlapping();

// Daily retention cleanup at midnight
Schedule::command('uptime:retention')->dailyAt('00:00');
