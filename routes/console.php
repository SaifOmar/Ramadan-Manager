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

Schedule::call(function () {
    $res = Http::get('https://api.aladhan.com/v1/timingsByCity/28-02-2025?city=Cairo&country=Egypt&method=2');
    if ($res->status() === 200) {
        PrayerTimings::updateOrCreate(["id" => 1], ["timings" => $res->json()['data']['timings']]);
    }
})->dailyAt('05:00')->timezone('Africa/Cairo');

Schedule::call(function () {
    $tasks = Task::all();
    foreach ($tasks as $task) {
        if ($task->type === "salah") {
            try {
                $prayer = ucfirst($task->title);
                $timings = PrayerTimings::find(1)->timings;
                $task->update(['expiry' => $timings[$prayer]]);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                continue;
            }
        }
        if ($task->expiry > date("H:i") && $task->status == 'waiting') {
            continue;
        }
        $task->update(['status' => 'waiting']);
    }
})->dailyAt('05:00')->timezone('Africa/Cairo');
