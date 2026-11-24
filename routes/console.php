<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('invoice:notify-due')->dailyAt('08:00');

try {
    $time = DB::table('pengaturan')->value('backup_schedule_time') ?? '02:00';
} catch (Exception $e) {
    $time = '02:00';
}

Schedule::command('backup:database')->monthlyOn(1, $time);
