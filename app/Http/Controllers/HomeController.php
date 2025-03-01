<?php

namespace App\Http\Controllers;

use App\Actions\RefreshUserTasks;
use App\Days;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, RefreshUserTasks $refreshUserTasks): View
    {
        $user = $request->user();
        $today = Days::from(now()->dayOfWeek());
        $tasks = Task::where('user_id', $user->id)->whereJsonContains('repeats', $today->value)->get();
        $completedTasks = $tasks->where('status', 'done');
        $missedTasks = $tasks->where('status', 'missed');
        $waitingTasks = $tasks->where('status', 'waiting');

        return view('home', compact('tasks', 'completedTasks', 'missedTasks', 'waitingTasks'));
    }
}
