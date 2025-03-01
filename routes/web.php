<?php

use App\Actions\CreateStarterPacks;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Models\PrayerTimings;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::get('/dashboard', HomeController::class)->name('home')->middleware('auth');

Route::prefix("tasks")->middleware('auth')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::get('/show/{task:id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/edit/{task:id}', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::post('/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/update/{task:id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/delete/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::patch('/{task}/incomplete', [TaskController::class, 'incomplete'])->name('tasks.incomplete');
    Route::patch('/{task}/reschedule', [TaskController::class, 'reschedule'])->name('tasks.reschedule');
});

Route::get('/create-defaults/{token}', function (string $token) {
    if ($token !== env('CREATE_DEFAULTS_TOKEN')) {
        abort(403);
    }
    CreateStarterPacks::generateDefaultsForAllUsers();
    return response()->json(['message' => 'Defaults created successfully']);
});

// Route::get('/refresh', function () {
//     $res = Http::get('https://api.aladhan.com/v1/timingsByCity/28-02-2025?city=Cairo&country=Egypt');
//     if ($res->status() === 200) {
//         PrayerTimings::updateOrCreate(["id" => 1], ["timings" => $res->json()['data']['timings']]);
//     }
//     foreach (User::all() as $user) {
//         $tasks = Task::where('user_id', $user->id)->get();
//         foreach ($tasks as $task) {
//             if ($task->type === "salah") {
//                 try {
//                     $prayer = ucfirst($task->title);
//                     $timings = PrayerTimings::find(1)->timings;
//                     $task->update(['expiry' => $timings[$prayer]]);
//                 } catch (\Exception $e) {
//                     Log::error($e->getMessage());
//                     continue;
//                 }
//             }
//             if ($task->isToday) {
//                 if ($task->expiry > date("H:i") && $task->status == 'waiting') {
//                     continue;
//                 }
//                 $task->update(['status' => 'waiting']);
//             }
//         }
//     }
//     return response()->json(['message' => 'Prayer timings updated successfully']);
// });

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


require __DIR__ . '/auth.php';
