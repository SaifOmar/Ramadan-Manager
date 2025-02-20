<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {

        $tasks = Task::all();
        $todaysTasks = $tasks;
        $completedTasks = $tasks->where('status', 'done');
        $missedTasks = $tasks->where('status', 'missed');
        $waitingTasks = $tasks->where('status', 'waiting');

        return view('home', compact('tasks', 'todaysTasks', 'completedTasks', 'missedTasks', 'waitingTasks'));
    }
}
