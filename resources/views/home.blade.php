<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard - Task Manager')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Today's Tasks</h1>
            <a href="{{ route('tasks.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Task
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-lg font-medium text-gray-600">Today's Tasks</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $tasks ? $tasks->count() : 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-lg font-medium text-gray-600">Completed</h3>
                <p class="text-2xl font-bold text-green-600">{{ $completedTasks ? $completedTasks->count() : 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-lg font-medium text-gray-600">Waiting</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $waitingTasks ? $waitingTasks->count() : 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-lg font-medium text-gray-600">Missed</h3>
                <p class="text-2xl font-bold text-red-600">{{ $missedTasks ? $missedTasks->count() : 0 }}</p>
            </div>
        </div>

        <!-- Task Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Active Tasks -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Current Tasks</h2>
                @forelse($tasks->where('status', 'waiting') as $task)
                    <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <form action="{{ route('tasks.complete', $task->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-6 h-6 rounded-full border-2 border-gray-300 hover:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                    </button>
                                </form>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-800">{{ $task->title }}</h3>
                                    <span class="text-sm text-gray-500">Due at
                                        {{ $task->expiry }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                                    {{ ucfirst($task->type) }}
                                </span>
                                <a href="{{ route('tasks.show', $task->id) }}" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <p class="text-gray-600">No active tasks</p>
                    </div>
                @endforelse
            </div>

            <!-- Completed Tasks -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Completed Today</h2>
                @forelse($completedTasks->where('updated_at' , '>', now()->subDay()) as $task)
                    <div class="bg-gray-50 rounded-lg shadow-md p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-600 line-through">{{ $task->title }}</h3>
                                    <span class="text-sm text-gray-500">Completed at
                                        {{ $task->updated_at->subHours(24)->format('H:i') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-sm rounded-full bg-green-100 text-green-800">
                                    {{ ucfirst($task->type) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <p class="text-gray-600">No completed tasks today</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Overdue Tasks Section -->
        @if ($tasks->where('status', 'missed')->count() > 0)
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Missed Tasks</h2>
                <div class="bg-red-50 rounded-lg shadow-md p-4">
                    <div class="space-y-4">
                        @foreach ($tasks->where('status', 'missed') as $task)
                            <div class="flex items-center justify-between border-b border-red-100 last:border-0 py-2">
                                <div class="flex items-center space-x-4">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    <div>
                                        <h3 class="text-lg font-medium text-red-800">{{ $task->title }}</h3>
                                        <span class="text-sm text-red-600">Missed at
                                            {{ $task->expiry }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('tasks.reschedule', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors text-sm">
                                            Reschedule
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
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
@endsection
