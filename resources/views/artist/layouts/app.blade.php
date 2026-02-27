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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

        /* Collapsible Sidebar */
        .sidebar {
            width: 80px;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar:hover {
            width: 260px;
        }

        .sidebar .sidebar-text {
            opacity: 0;
            white-space: nowrap;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .sidebar-text {
            opacity: 1;
        }

        .sidebar .sidebar-logo-text {
            opacity: 0;
            overflow: hidden;
            white-space: nowrap;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .sidebar-logo-text {
            opacity: 1;
        }

        /* Tooltip for collapsed sidebar */
        .sidebar-link .sidebar-tooltip {
            display: none;
            position: absolute;
            left: 72px;
            top: 50%;
            transform: translateY(-50%);
            background: #C4A35A;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            white-space: nowrap;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            pointer-events: none;
        }

        .sidebar-link .sidebar-tooltip::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: #C4A35A;
            border-left: none;
        }

        .sidebar:not(:hover) .sidebar-link:hover .sidebar-tooltip {
            display: block;
        }

        .sidebar-link {
            position: relative;
            transition: all 0.2s ease;
        }

        /* Main content transition */
        .main-content {
            margin-left: 80px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar:hover ~ .main-wrapper .main-content {
            margin-left: 260px;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="sidebar bg-white min-h-screen shadow-sm fixed left-0 top-0 hidden lg:block overflow-hidden z-30">
            <div class="p-5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                    <img src="{{ asset('uploads/logo.png') }}" alt="Gamartisan" class="w-10 h-10 object-contain">
                </div>
                <div class="sidebar-logo-text">
                    <a href="{{ route('home') }}" class="text-xl font-display font-bold text-ochre leading-tight">Gamartisan</a>
                    <p class="text-xs text-gray-400">Artist Dashboard</p>
                </div>
            </div>

            <nav class="mt-2 px-3 space-y-1">
                <a href="{{ route('artist.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-ochre/10 hover:text-ochre transition-colors {{ request()->routeIs('artist.dashboard') ? 'bg-ochre/10 text-ochre border-r-4 border-ochre' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="sidebar-text">Dashboard</span>
                    <span class="sidebar-tooltip">Dashboard</span>
                </a>
                <a href="{{ route('artist.products') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-ochre/10 hover:text-ochre transition-colors {{ request()->routeIs('artist.products*') ? 'bg-ochre/10 text-ochre border-r-4 border-ochre' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="sidebar-text">My Products</span>
                    <span class="sidebar-tooltip">My Products</span>
                </a>
                <a href="{{ route('artist.orders') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-ochre/10 hover:text-ochre transition-colors {{ request()->routeIs('artist.orders') ? 'bg-ochre/10 text-ochre border-r-4 border-ochre' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span class="sidebar-text">Orders</span>
                    <span class="sidebar-tooltip">Orders</span>
                </a>
                <a href="{{ route('artist.profile') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-ochre/10 hover:text-ochre transition-colors {{ request()->routeIs('artist.profile') ? 'bg-ochre/10 text-ochre border-r-4 border-ochre' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="sidebar-text">Profile</span>
                    <span class="sidebar-tooltip">Profile</span>
                </a>
                <a href="{{ route('artist.transactions') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-ochre/10 hover:text-ochre transition-colors {{ request()->routeIs('artist.transactions') ? 'bg-ochre/10 text-ochre border-r-4 border-ochre' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="sidebar-text">Transaction Logs</span>
                    <span class="sidebar-tooltip">Transaction Logs</span>
                </a>
                <a href="{{ route('artist.reviews') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-ochre/10 hover:text-ochre transition-colors {{ request()->routeIs('artist.reviews') ? 'bg-ochre/10 text-ochre border-r-4 border-ochre' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    <span class="sidebar-text">Reviews</span>
                    <span class="sidebar-tooltip">Reviews</span>
                </a>
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-gray-100">
                <a href="{{ route('home') }}" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:text-ochre">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="sidebar-text">View Store</span>
                    <span class="sidebar-tooltip">View Store</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-red-600 hover:text-red-700 w-full">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="sidebar-text">Logout</span>
                        <span class="sidebar-tooltip">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-wrapper flex-1">
            <main class="main-content">
                <!-- Top Header -->
                <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
                    <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    <div class="flex items-center gap-4">
                    @include('partials.notification-bell')
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
    </div>
    @stack('scripts')
</body>
</html>
