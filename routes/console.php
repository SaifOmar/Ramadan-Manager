<?php

use App\Models\Task;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
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
