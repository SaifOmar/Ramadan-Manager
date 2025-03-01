<?php

use App\Models\PrayerTimings;
use App\Models\Task;
use App\Prayers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

// Updates the prayer timings table daily
Schedule::call(function () {
    $res = Http::get('https://api.aladhan.com/v1/timingsByCity/28-02-2025?city=Cairo&country=Egypt');
    if ($res->status() === 200) {
        PrayerTimings::updateOrCreate(["id" => 1], ["timings" => $res->json()['data']['timings']]);
    }
})->dailyAt('05:00')->timezone('Africa/Cairo');

Schedule::call(function () {
    $tasks = Task::all();
    // Updates the prayer timings for each user daily
    foreach ($tasks as $task) {
        if ($task->type === "prayer") {
            try {
                $prayer = ucfirst($task->title);
                $timings = PrayerTimings::find(1)->timings;
                $task->update(['expiry' => $timings[$prayer]]);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                continue;
            }
        }
        // Updates the status of each task daily based on day and expiry time
        if ($task->isToday) {
            if ($task->expiry > date("H:i") && $task->status == 'waiting') {
                continue;
            }
            $task->update(['status' => 'waiting']);
        }
    }
})->dailyAt('05:00')->timezone('Africa/Cairo');
