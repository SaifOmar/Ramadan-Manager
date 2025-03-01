<!-- resources/views/tasks/index.blade.php -->
@extends('layouts.app')

@section('title', 'Tasks - Task Manager')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">My Tasks</h1>
            <a href="{{ route('tasks.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Task
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @php
            // Group tasks by day
            $tasksByDay = [];
            foreach (\App\Days::cases() as $day) {
                $tasksByDay[$day->value] = [
                    'name' => $day->name,
                    'tasks' => [],
                ];
            }

            // Populate tasks for each day
            foreach ($tasks as $task) {
                $repeats = $task->repeats;
                if ($repeats) {
                    foreach ($repeats as $dayNumber) {
                        $tasksByDay[$dayNumber]['tasks'][] = $task;
                    }
                }
            }

            // Calculate total tasks per day
            $totalTasksByDay = [];
            foreach ($tasksByDay as $dayNumber => $dayData) {
                $totalTasksByDay[$dayNumber] = count($dayData['tasks']);
            }

            // Current time for comparing with expiry
            $currentTime = now();
        @endphp

        <!-- Day tabs with counts -->
        <div class="bg-white rounded-lg shadow-md mb-8 overflow-hidden">
            <div class="flex overflow-x-auto">
                @foreach ($tasksByDay as $dayNumber => $dayData)
                    <a href="#day-{{ $dayNumber }}"
                        class="px-6 py-4 border-b-2 font-medium text-sm flex items-center transition-colors whitespace-nowrap
                            {{ request()->segment(2) == $dayNumber || (request()->segment(2) == null && $loop->first)
                                ? 'border-blue-500 text-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        {{ $dayData['name'] }}
                        <span class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">
                            {{ $totalTasksByDay[$dayNumber] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Day sections with tasks -->
        @foreach ($tasksByDay as $dayNumber => $dayData)
            <div id="day-{{ $dayNumber }}" class="mb-10">
                <div class="flex items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $dayData['name'] }}'s Tasks</h2>
                    <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                        {{ count($dayData['tasks']) }} tasks
                    </span>
                </div>

                @if (count($dayData['tasks']) > 0)
                    <div class="space-y-4">
                        @foreach ($dayData['tasks'] as $task)
                            @php
                                // Check if task is missed (past expiry time and not done)
                                $isMissed =
                                    $task->status !== 'done' &&
                                    \Carbon\Carbon::parse(date('Y-m-d') . ' ' . $task->expiry)->isPast();
                            @endphp
                            <div
                                class="bg-white rounded-lg shadow-md p-5 hover:shadow-lg transition-shadow
                                {{ $isMissed ? 'border-l-4 border-red-500' : '' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        @if ($task->status !== 'done')
                                            <form action="{{ route('tasks.complete', $task->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="w-6 h-6 rounded-full border-2 {{ $isMissed ? 'border-red-500' : 'border-gray-300' }} hover:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                                </button>
                                            </form>
                                        @else
                                            <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h3
                                                class="text-lg font-medium {{ $task->status === 'done' ? 'text-gray-600 line-through' : ($isMissed ? 'text-red-600' : 'text-gray-800') }}">
                                                {{ $task->title }}
                                            </h3>
                                            <span
                                                class="text-sm {{ $isMissed ? 'text-red-500 font-medium' : 'text-gray-500' }}">
                                                {{ $task->status === 'done' ? 'Completed' : ($isMissed ? 'Missed' : 'Due') }}
                                                at
                                                {{ $task->expiry }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="px-2 py-1 text-sm rounded-full
                                            @if ($task->status === 'done') bg-green-100 text-green-800
                                            @elseif($task->status === 'waiting') bg-yellow-100 text-yellow-800
                                            @elseif($isMissed) bg-red-100 text-red-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($task->type) }}
                                        </span>

                                        <div class="flex space-x-2">
                                            <a href="{{ route('tasks.show', $task->id) }}"
                                                class="p-1 text-gray-400 hover:text-blue-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            @if ($task->status === 'done')
                                                <form action="{{ route('tasks.incomplete', $task->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="p-1 text-gray-400 hover:text-yellow-600 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1 text-gray-400 hover:text-red-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <p class="text-gray-600">No tasks scheduled for {{ $dayData['name'] }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Quick Task Button -->
    <div class="fixed bottom-8 right-8">
        <button onclick="document.getElementById('quickTaskModal').classList.remove('hidden')"
            class="w-14 h-14 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors shadow-lg flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </div>

    <!-- Quick Task Modal -->
    <div id="quickTaskModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-800">Quick Add Task</h3>
                <button onclick="document.getElementById('quickTaskModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <input type="text" name="title" placeholder="Task title"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    <div class="flex space-x-2">
                        <select name="type"
                            class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">Type</option>
                            <option value="prayer">Prayer</option>
                            <option value="quran">Quran</option>
                            <option value="food">Food</option>
                            <option value="work">Work</option>
                            <option value="sleep">Sleep</option>
                        </select>
                        <input type="time" name="expiry"
                            class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Repeat on days:</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach (\App\Days::cases() as $day)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="repeats[]" value="{{ $day->value }}"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">{{ $day->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Add Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Smooth scroll to section when clicking tab
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('[href^="#day-"]');
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });

                        // Update active tab
                        tabs.forEach(t => t.classList.remove('border-blue-500', 'text-blue-600'));
                        tabs.forEach(t => t.classList.add('border-transparent', 'text-gray-500'));
                        this.classList.remove('border-transparent', 'text-gray-500');
                        this.classList.add('border-blue-500', 'text-blue-600');
                    }
                });
            });
        });
    </script>
@endsection
