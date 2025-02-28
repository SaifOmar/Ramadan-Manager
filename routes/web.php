<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Models\PrayerTimings;
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
    Route::patch('/{task}/reschedule', [TaskController::class, 'reschedule'])->name('tasks.reschedule');
});


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


require __DIR__ . '/auth.php';
