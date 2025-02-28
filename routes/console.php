<?php

use App\Models\PrayerTimings;
use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

Schedule::call(function () {
    $tasks = Task::all();
    foreach ($tasks as $task) {
        if ($task->expiry > date("H:i") && $task->status == 'waiting') {
            continue;
        }
        $task->update(['status' => 'waiting']);
    }
})->dailyAt('05:00')->timezone('Africa/Cairo');

Schedule::call(function () {
    $res = Http::get('https://api.aladhan.com/v1/timingsByCity/28-02-2025?city=Cairo&country=Egypt&method=2');
    if ($res->status() === 200) {
        $timings =  PrayerTimings::create(["timings" => $res->json()['data']['timings']]);
    }
})->dailyAt('05:00')->timezone('Africa/Cairo');
