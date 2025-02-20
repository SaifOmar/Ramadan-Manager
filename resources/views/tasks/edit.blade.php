@extends('layouts.app')
@section('title', 'Create Task - Task Manager')
@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Create New Task</h1>
        <!-- Global Error Alert -->
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

        <form action="{{ route('tasks.update', ['task' => $task->id]) }}" method="POST"
            class="bg-white rounded-lg shadow-md p-6">
            @csrf
            @method('PATCH')
            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Task Title</label>
                <input type="text" id="title" name="title"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                    placeholder="Enter task title" value="{{ old('title') ?? $task->title }}" required>
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                    placeholder="Enter task description">{{ old('description') ?? $task->description }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <!-- Task Type -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Task Type</label>
                <select id="type" name="type"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror"
                    required>
                    <option value="" selected>Select a type</option>
                    <option value="salah" {{ old('type') == 'salah' || $task->type == 'salah' ? 'selected' : '' }}>Salah
                    </option>
                    <option value="quran" {{ old('type') == 'quran' || $task->type == 'quran' ? 'selected' : '' }}>Quran
                    </option>
                    <option value="food" {{ old('type') == 'food' || $task->type == 'food' ? 'selected' : '' }}>Food
                    </option>
                    <option value="work" {{ old('type') == 'work' || $task->type == 'work' ? 'selected' : '' }}>Work
                    </option>
                    <option value="sleep" {{ old('type') == 'sleep' || ($task->type = 'sleep' ? 'selected' : '') }}>Sleep
                    </option>
                </select>
                @error('type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <!-- Due Time -->
            <div class="mb-6">
                <label for="expiry" class="block text-sm font-medium text-gray-700 mb-2">Due Time</label>
                <input type="time" id="expiry" name="expiry"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('expiry') border-red-500 @enderror"
                    value="{{ old('expiry') ?? $task->expiry }}" required>
                @error('expiry')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Create Task
                </button>
            </div>
        </form>
    </div>
@endsection
