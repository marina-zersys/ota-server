<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTA Dashboard - @yield('title', 'Home')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-900">OTA Server</h1>
                <p class="text-sm text-gray-500 mt-1">Release Dashboard</p>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('dashboard.apps') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard.apps') || request()->routeIs('dashboard.app-detail') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Apps
                </a>
            </nav>
        </aside>

        {{-- Main content --}}
        <main class="flex-1 p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
