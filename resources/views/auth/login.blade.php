<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ramadan Manager - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-6">
                <div class="flex justify-center">
                    <svg class="w-20 h-20" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Crescent moon -->
                        <path
                            d="M75 50C75 63.8071 63.8071 75 50 75C36.1929 75 25 63.8071 25 50C25 36.1929 36.1929 25 50 25C63.8071 25 75 36.1929 75 50Z"
                            fill="#4B5563" />
                        <path
                            d="M65 50C65 58.2843 58.2843 65 50 65C41.7157 65 35 58.2843 35 50C35 41.7157 41.7157 35 50 35C55.5 35 65 41.7157 65 50Z"
                            fill="#F3F4F6" />
                        <!-- Stars -->
                        <circle cx="20" cy="30" r="2" fill="#F59E0B" />
                        <circle cx="80" cy="25" r="2" fill="#F59E0B" />
                        <circle cx="85" cy="40" r="2" fill="#F59E0B" />
                        <circle cx="75" cy="65" r="2" fill="#F59E0B" />
                        <circle cx="25" cy="70" r="2" fill="#F59E0B" />
                        <!-- Lantern -->
                        <rect x="47" y="10" width="6" height="4" rx="1" fill="#78350F" />
                        <path
                            d="M45 14H55C56.1046 14 57 14.8954 57 16V22C57 23.1046 56.1046 24 55 24H45C43.8954 24 43 23.1046 43 22V16C43 14.8954 43.8954 14 45 14Z"
                            fill="#F59E0B" />
                        <path d="M47 24H53V26C53 27.1046 52.1046 28 51 28H49C47.8954 28 47 27.1046 47 26V24Z"
                            fill="#78350F" />
                        <rect x="48" y="14" width="1" height="10" fill="#FBBF24" />
                        <rect x="51" y="14" width="1" height="10" fill="#FBBF24" />
                    </svg>
                </div>
                <h2 class="mt-4 text-2xl font-bold text-gray-800">Ramadan Manager</h2>
                <p class="text-gray-600">Sign in to your account</p>
            </div>

            <!-- Google Sign-in Button -->
            <div class="mb-6">
                <a href="{{ route('login.google') }}"
                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path
                            d="M12.24 10.285V14.4h6.806c-.275 1.765-2.056 5.174-6.806 5.174-4.095 0-7.439-3.389-7.439-7.574s3.345-7.574 7.439-7.574c2.33 0 3.891.989 4.785 1.849l3.254-3.138C18.189 1.186 15.479 0 12.24 0c-6.635 0-12 5.365-12 12s5.365 12 12 12c6.926 0 11.52-4.869 11.52-11.726 0-.788-.085-1.39-.189-1.989H12.24z"
                            fill="#4285F4" />
                        <path
                            d="M12.24 10.285V14.4h6.806c-.275 1.765-2.056 5.174-6.806 5.174-4.095 0-7.439-3.389-7.439-7.574s3.345-7.574 7.439-7.574c2.33 0 3.891.989 4.785 1.849l3.254-3.138C18.189 1.186 15.479 0 12.24 0c-6.635 0-12 5.365-12 12s5.365 12 12 12c6.926 0 11.52-4.869 11.52-11.726 0-.788-.085-1.39-.189-1.989H12.24z"
                            fill="#4285F4" />
                        <path d="M7 11v2h4.474v-2H7zm0 4v2h4.474v-2H7z" fill="#34A853" />
                        <path d="M7 7v2h10v-2H7z" fill="#FBBC05" />
                        <path d="M7 15v2h10v-2H7z" fill="#EA4335" />
                    </svg>
                    Sign in with Google
                </a>
            </div>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with email</span>
                </div>
            </div>

            <form method="POST" action="/login">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        type="email" name="email" required autocomplete="email" autofocus>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        type="password" name="password" required autocomplete="current-password">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-4 flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        name="remember">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-600">Remember me</label>
                </div>

                <div class="flex items-center justify-center mb-6">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Login
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a class="text-blue-600 hover:text-blue-800 transition-colors" href="/register">
                            Register
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
