<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Artist Dashboard') - Gamartisan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'forest': '#2D5A3D',
                        'forest-dark': '#1E3D2A',
                        'cream': '#FAF8F5',
                        'sand': '#E8E4DD',
                        'ochre': '#C4A35A',
                    },
                    fontFamily: {
                        'display': ['Playfair Display', 'serif'],
                        'body': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white min-h-screen shadow-sm fixed left-0 top-0 hidden lg:block">
            <div class="p-6">
                <a href="{{ route('home') }}" class="text-2xl font-display font-bold text-ochre">Gamartisan</a>
                <p class="text-sm text-gray-500 mt-1">Artist Dashboard</p>
            </div>

            <nav class="mt-6">
                <a href="{{ route('artist.dashboard') }}" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-ochre/10 hover:text-ochre transition-colors {{ request()->routeIs('artist.dashboard') ? 'bg-ochre/10 text-ochre border-r-4 border-ochre' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('artist.products') }}" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-ochre/10 hover:text-ochre transition-colors {{ request()->routeIs('artist.products*') ? 'bg-ochre/10 text-ochre border-r-4 border-ochre' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    My Products
                </a>
                <a href="{{ route('artist.orders') }}" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-ochre/10 hover:text-ochre transition-colors {{ request()->routeIs('artist.orders') ? 'bg-ochre/10 text-ochre border-r-4 border-ochre' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Orders
                </a>
                <a href="{{ route('artist.profile') }}" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-ochre/10 hover:text-ochre transition-colors {{ request()->routeIs('artist.profile') ? 'bg-ochre/10 text-ochre border-r-4 border-ochre' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile
                </a>
                <a href="{{ route('artist.transactions') }}" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-ochre/10 hover:text-ochre transition-colors {{ request()->routeIs('artist.transactions') ? 'bg-ochre/10 text-ochre border-r-4 border-ochre' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Transaction Logs
                </a>
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:text-ochre mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    View Store
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-2 text-red-600 hover:text-red-700 w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64">
            <!-- Top Header -->
            <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">Hello, {{ session('user_name') }}!</span>
                    <div class="w-10 h-10 bg-ochre/20 rounded-full flex items-center justify-center">
                        <span class="text-ochre font-semibold">{{ strtoupper(substr(session('user_name'), 0, 1)) }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
