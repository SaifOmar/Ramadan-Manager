<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'Home - Task Manager')

@section('content')
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Tasks Dashboard</h1>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($data as $item)
            <div class="bg-white rounded-lg shadow-md p-6 transition-transform hover:transform hover:scale-105">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $item->title }}</h2>
                <div class="flex items-center">
                    <span class="text-gray-600">Description:</span>
                    <span class="ml-2 text-gray-800">{{ $item->description ?? 'No description' }}</span>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <span class="text-gray-600">Expires:</span>
                        <span class="ml-2 text-gray-800">{{ $item->expiry }}</span>
                    </div>

                    <div class="flex items-center">
                        <span class="text-gray-600">Type:</span>
                        <span class="ml-2 text-gray-800">{{ $item->type }}</span>
                    </div>

                    <div class="flex items-center">
                        <span class="text-gray-600">Status:</span>
                        <span
                            class="ml-2 px-2 py-1 text-sm rounded-full
                            @if ($item->status === 'done') bg-green-100 text-green-800
                            @elseif($item->status === 'waiting')
                                bg-yellow-100 text-yellow-800
                            @else
                                bg-red-100 text-red-800 @endif
                        ">
                            {{ $item->status }}
                        </span>
                    </div>
                    <!-- view button here with good css -->
                </div>
                <div class="flex gap-3 mt-4">

                    <div class="mt-4">
                        <a href="{{ route('tasks.show', $item->id) }}"
                            class="bg-green-400 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
                            View </a>
                    </div>
                    <div class="mt-2">
                        <form method="POST" action="{{ route('tasks.destroy', ['task' => $item->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-400 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
                                Delete
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
