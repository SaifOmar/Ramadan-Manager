<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('title', 'Welcome - Ramadan Manager')

@section('content')
    <!-- Hero Section -->
    <div class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
            <div class="md:flex md:items-center md:justify-between">
                <div class="md:w-1/2">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Ramadan Manager
                    </h1>
                    <p class="text-xl md:text-2xl text-blue-100 mb-8">
                        Organize your Ramadan journey with a personal task manager designed to help you make the most of
                        this blessed month.
                    </p>
                    @guest
                        <div class="space-x-4">
                            <a href="{{ route('register') }}"
                                class="inline-block bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold text-lg transition-colors">
                                Sign Up
                            </a>
                            <a href="{{ route('login') }}"
                                class="inline-block bg-blue-700 text-white hover:bg-blue-800 px-8 py-3 rounded-lg font-semibold text-lg transition-colors">
                                Login
                            </a>
                        </div>
                    @endguest
                    @auth
                        <a href="{{ route('home') }}"
                            class="inline-block bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold text-lg transition-colors">
                            Go to Dashboard
                        </a>
                    @endauth
                </div>
                <div class="hidden md:block md:w-1/2">
                    <svg class="w-full h-64 text-blue-100 opacity-50" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>


    <!-- How It Works -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">
                How It Works
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Create Account</h3>
                    <p class="text-gray-600">Sign up for your personal account to get started with Ramadan Manager.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Add Your Tasks</h3>
                    <p class="text-gray-600">Create and organize your daily Ramadan tasks and worship schedule.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Track Progress</h3>
                    <p class="text-gray-600">Monitor your achievements and stay consistent throughout Ramadan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                Ready to Make This Ramadan More Productive?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Join thousands of Muslims using Ramadan Manager to organize their worship and maximize their blessings.
            </p>
            @guest
                <a href="{{ route('register') }}"
                    class="inline-block bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold text-lg transition-colors">
                    Get Started Now
                </a>
            @endguest
            @auth
                <a href="{{ route('home') }}"
                    class="inline-block bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold text-lg transition-colors">
                    Go to Dashboard
                </a>
            @endauth
        </div>
    </div>
@endsection
