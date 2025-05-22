<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main content -->
        <div class="flex flex-col flex-1">
            <header class="p-4 bg-white shadow">
                <!-- Header content -->
                <h2 class="text-xl font-semibold">Admin Panel</h2>
            </header>
            <main class="flex-1 p-6">
                @yield('content')
            </main>
            <footer class="p-4 bg-white shadow">
                <!-- Footer content -->
                <p class="text-sm text-center text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                </p>
            </footer>
        </div>
    </div>
</body>
</html>
