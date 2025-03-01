@extends('layouts.app')
@section('title', 'Edit Task - Task Manager')
@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Edit Task</h1>
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
            <!-- Weekday Selection -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">Repeat on Days</label>
                    <button type="button" id="toggle-all-days"
                        class="text-sm text-blue-600 hover:text-blue-800 focus:outline-none">
                        Toggle All
                    </button>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2">
                    @php
                        $weekdays = \App\Days::cases();

                        // Get repeats from old input or task's repeats (decode JSON if needed)
$taskRepeats = old('repeats', $task->repeats ?? []);

// Convert to array if it's a JSON string
                        if (is_string($taskRepeats) && json_decode($taskRepeats) !== null) {
                            $taskRepeats = json_decode($taskRepeats, true);
                        } elseif (!is_array($taskRepeats)) {
                            $taskRepeats = [];
                        }

                        // Convert to strings for comparison
                        $taskRepeats = array_map('strval', $taskRepeats);
                    @endphp

                    @foreach ($weekdays as $day)
                        <div class="flex items-center p-2 border rounded-md hover:bg-gray-50">
                            <input type="checkbox" id="weekday-{{ $day->value }}" name="repeats[]"
                                value="{{ $day->value }}"
                                class="weekday-checkbox h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                {{ in_array((string) $day->value, $taskRepeats) ? 'checked' : '' }}>
                            <label for="weekday-{{ $day->value }}"
                                class="ml-3 block text-sm text-gray-700 truncate cursor-pointer">
                                {{ $day->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('repeats')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Task Type -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Task Type</label>
                <select id="type" name="type"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror"
                    required>
                    <option value="">Select a type</option>
                    <option value="salah" {{ (old('type') ?? $task->type) == 'salah' ? 'selected' : '' }}>Prayer</option>
                    <option value="quran" {{ (old('type') ?? $task->type) == 'quran' ? 'selected' : '' }}>Quran</option>
                    <option value="food" {{ (old('type') ?? $task->type) == 'food' ? 'selected' : '' }}>Food</option>
                    <option value="work" {{ (old('type') ?? $task->type) == 'work' ? 'selected' : '' }}>Work</option>
                    <option value="sleep" {{ (old('type') ?? $task->type) == 'sleep' ? 'selected' : '' }}>Sleep</option>
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
                    Update Task
                </button>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleAllBtn = document.getElementById('toggle-all-days');
            const checkboxes = document.querySelectorAll('.weekday-checkbox');

            // Check initial state
            function updateToggleButtonText() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                toggleAllBtn.textContent = allChecked ? 'Unselect All' : 'Select All';
                return allChecked;
            }

            let allChecked = updateToggleButtonText();

            // Make labels clickable to toggle checkboxes
            document.querySelectorAll('.weekday-checkbox + label').forEach(label => {
                label.addEventListener('click', function(event) {
                    const checkbox = document.getElementById(this.getAttribute('for'));
                    checkbox.checked = !checkbox.checked;
                    updateToggleButtonText();

                    // Prevent default to avoid double toggle from label association
                    event.preventDefault();
                });
            });

            toggleAllBtn.addEventListener('click', function() {
                // Toggle the state based on current state
                allChecked = !allChecked;

                // Apply the new state to all checkboxes
                checkboxes.forEach(checkbox => {
                    checkbox.checked = allChecked;
                });

                // Update button text
                updateToggleButtonText();
            });

            // Make the whole div clickable
            document.querySelectorAll('.weekday-checkbox').forEach(checkbox => {
                const container = checkbox.closest('div');
                container.addEventListener('click', function(e) {
                    // Only toggle if the click wasn't directly on the checkbox (which handles itself)
                    if (e.target !== checkbox) {
                        checkbox.checked = !checkbox.checked;
                        updateToggleButtonText();
                    }
                });
            });
        });
    </script>
@endsection
