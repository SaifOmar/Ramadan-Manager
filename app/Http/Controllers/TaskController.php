<?php

namespace App\Http\Controllers;

use App\Actions\RefreshUserTasks;
use App\Days;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, RefreshUserTasks $refreshUserTasks): View
    {
        $tasks = $refreshUserTasks->handle($request->user());
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|View
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'string|max:1000',
            'expiry' => 'required|date_format:H:i',
            'type' => 'required|string',
            'repeats' => 'required|array',
        ]);
        try {
            if (!Auth::user()) {
                return redirect()->back()->withErrors(['error' => 'You are not authorized to create a task']);
            }

            $validated['user_id'] = $request->user()->id;
            $validated['status'] = 'waiting';
            $validated['repeats'] = $this->toEnum($validated['repeats']);

            if ($validated['expiry'] < date('H:i')) {
                $validated['status'] = 'missed';
            }
            Task::create($validated);
            return redirect()->back()->with('success', 'Task created successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Task creation failed']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403, 'You are not authorized to view this task');
        }
        if ($task->status === 'waiting' && $task->expiry < date('H:i', strtotime('+2 hour'))) {
            $task->update(['status' => 'missed']);
        }
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Task $task): View
    {
        $reps  = $task->repeats;

        $days = Days::cases();
        foreach ($days as $index => $day) {

            if (array_key_exists($index, $reps)) {
                // dd($day);
            }
        }
        // dd($day, $reps);

        if ($task->user_id !== $request->user()->id) {
            abort(403, 'You are not authorized to view this task');
        }
        return view('tasks.edit', compact('task'));
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse|View
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'expiry' => 'required|date_format:H:i',
            'type' => 'required|string',
            'repeats' => 'required|array',
        ]);
        try {
            $validated['repeats'] = $this->toEnum($validated['repeats']);
            $task->update($validated);
            return redirect()->back()->with('success', 'Task updated successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Task update failed']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::user()->id) {
            abort(403, 'You are not authorized to view this task');
        }
        try {
            $task->delete();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e]);
        }
        return redirect()->route("home")->with(['success' => 'Task deleted successfully']);
    }
    public function incomplete(Task $task)
    {
        if ($task->user_id !== Auth::user()->id) {
            abort(403, 'You are not authorized to view this task');
        }
        $task->update(['status' => 'waiting']);
        return back();
    }
    public function complete(Task $task)
    {
        if ($task->user_id !== Auth::user()->id) {
            abort(403, 'You are not authorized to view this task');
        }
        $task->update(['status' => 'done']);
        return back();
    }

    public function reschedule(Task $task)
    {
        if ($task->user_id !== Auth::user()->id) {
            abort(403, 'You are not authorized to view this task');
        }
        $task->update(['status' => 'waiting', 'expiry' => date('H:i', strtotime('+3 hour'))]);
        return back();
    }
    private function toEnum($days)
    {
        $repeats = [];
        foreach ($days as $day) {
            $repeats[] = Days::tryFrom($day);
        }
        return $repeats;
    }
}
