<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::get('/tasks/show/{task:title}', [TaskController::class, 'show'])->name('tasks.show');
Route::get('/tasks/edit/{task:title}', [TaskController::class, 'edit'])->name('tasks.edit');
Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');
Route::patch('/tasks/update/{task:id}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/delete/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
Route::patch('/tasks/{task}/reschedule', [TaskController::class, 'reschedule'])->name('tasks.reschedule');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
