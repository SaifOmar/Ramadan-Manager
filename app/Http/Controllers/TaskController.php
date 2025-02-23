<?php

namespace App\Http\Controllers;

use App\Actions\RefreshUserTasks;
use App\Models\Task;
use Illuminate\Http\Request;
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
        $data = $refreshUserTasks->handle($request->user());
        return view('tasks.index', compact('data'));
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
        ]);
        try {
            if (!Auth::user()) {
                return redirect()->back()->withErrors(['error' => 'You are not authorized to create a task']);
            }

            // if ($validated['expiry'] < now()) {
            //     return redirect()->back()->withErrors(['error' => 'Task expiry time cannot be in the past']);
            // }

            $validated['user_id'] = $request->user()->id;
            $validated['status'] = 'waiting';

            if ($validated['expiry'] < date('H:i')) {
                $validated['status'] = 'missed';
            }
            Task::create($validated);
            return redirect()->back()->with('success', 'Task created successfully');
        } catch (\Exception $e) {
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
        ]);
        try {
            $task->update($validated);
            return redirect()->back()->with('success', 'Task updated successfully');
        } catch (\Exception $e) {
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
}
