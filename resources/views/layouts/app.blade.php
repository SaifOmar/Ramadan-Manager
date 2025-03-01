<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Ramadan Manager')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar-mobile.active {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Mobile Header -->
    <div class="md:hidden bg-white shadow-md fixed w-full z-20">
        <div class="flex items-center justify-between p-4">
            <h1 class="text-xl font-bold text-gray-800">Ramadan Manager</h1>
            <button onclick="toggleSidebar()" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <div class="flex">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-white w-64 min-h-screen shadow-lg fixed z-30 sidebar-mobile md:translate-x-0">
            <div class="p-6">
                <!-- Close button for mobile -->
                <button onclick="toggleSidebar()"
                    class="md:hidden absolute top-4 right-4 text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h2 class="text-2xl font-bold text-gray-800 mb-6">Ramadan Manager</h2>
                <nav class="space-y-2">
                    <a href="{{ route('home') }}"
                        class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ Request::routeIs('home') ? 'bg-gray-100' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Home
                    </a>
                    <a href="{{ route('tasks.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ Request::routeIs('tasks.index') ? 'bg-gray-100' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Tasks
                    </a>
                    <a href="{{ route('tasks.create') }}"
                        class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ Request::routeIs('tasks.create') ? 'bg-gray-100' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Tasks
                    </a>
                    <!-- create a form for loggin out that looks like a nav item and it's at the bottom of the sidebar -->
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors w-full text-left">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 10a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    @endauth
                </nav>
            </div>
        </div>

        <!-- Overlay for mobile -->
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"
            onclick="toggleSidebar()">
        </div>

        <!-- Main Content -->
        <div class="w-full md:ml-64">
            <div class="md:p-8 p-4 mt-16 md:mt-0">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            sidebar.classList.toggle('active');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>

</html>
