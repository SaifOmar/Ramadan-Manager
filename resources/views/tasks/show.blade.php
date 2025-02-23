<!-- resources/views/tasks/show.blade.php -->
@extends('layouts.app')

@section('title', 'Task Details - Task Manager')

@section('content')
    <div class="max-w-4xl mx-auto">
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-500 text-green-700 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-500 text-red-700 p-4 rounded-lg">
                <p class="font-bold">Please correct the following errors:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Task Details</h1>
            <div class="space-x-4">
                <a href="{{ route('tasks.edit', ['task' => $task->id]) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Task
                </a>
                <form action="{{ route('tasks.destroy', ['task' => $task->id]) }} " method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors inline-flex items-center"
                        onclick="return confirm('Are you sure you want to delete this task?')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Task
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Title Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ $task->title }}</h2>
                <div class="flex items-center space-x-4">
                    <span
                        class="px-3 py-1 text-sm rounded-full
                        @if ($task->status === 'done') bg-green-100 text-green-800
                        @elseif($task->status === 'waiting')
                            bg-yellow-100 text-yellow-800
                        @else
                            bg-red-100 text-red-800 @endif">
                        {{ ucfirst($task->status) }}
                    </span>
                    <span class="text-gray-600">{{ ucfirst($task->type) }}</span>
                </div>
            </div>

            <!-- Description Section -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-2">Description</h3>
                <p class="text-gray-600 whitespace-pre-line">{{ $task->description ?: 'No description provided.' }}</p>
            </div>

            <!-- Time and Details Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Due Time</h3>
                        <p class="text-gray-600">{{ date('h:i A', strtotime($task->expiry)) }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Task Type</h3>
                        <p class="text-gray-600">{{ ucfirst($task->type) }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Created At</h3>
                        <p class="text-gray-600">{{ $task->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Last Updated</h3>
                        <p class="text-gray-600">{{ $task->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('home') }}"
                class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
@endsection
