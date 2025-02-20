<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Task::all();
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
            $validated['status'] = 'waiting';
            $task = Task::create($validated);
            return redirect()->back()->with('success', 'Task created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Task creation failed']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Task $task): View
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
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
        try {
            $task->delete();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e]);
        }
        return redirect()->route("home")->with(['success' => 'Task deleted successfully']);
    }
    public function complete(Task $task)
    {
        $task->update(['status' => 'done']);
        return back();
    }

    public function reschedule(Task $task)
    {
        $task->update(['status' => 'waiting']);
        return back();
    }
}
