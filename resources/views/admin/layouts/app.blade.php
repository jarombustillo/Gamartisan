<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Gamartisan</title>
    <meta name="description" content="Gamartisan Admin Dashboard">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'forest': '#2D5A3D',
                        'forest-dark': '#1E3D2A',
                        'forest-light': '#3D7A50',
                        'cream': '#FAF8F5',
                        'sand': '#F5F1EA',
                        'ochre': '#C4A35A',
                        'terracotta': '#C67B5C',
                        'sage': '#8FAE8B',
                    },
                    fontFamily: {
                        'body': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

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
            position: absolute;
            left: 52px;
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

        .sidebar .sidebar-section-label {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .sidebar-section-label {
            opacity: 1;
        }

        .sidebar-link {
            transition: all 0.2s ease;
            position: relative;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.12);
        }

        /* Tooltip for collapsed sidebar */
        .sidebar-link .sidebar-tooltip {
            display: none;
            position: absolute;
            left: 72px;
            top: 50%;
            transform: translateY(-50%);
            background: #1E3D2A;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            white-space: nowrap;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            pointer-events: none;
        }

        .sidebar-link .sidebar-tooltip::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: #1E3D2A;
            border-left: none;
        }

        .sidebar:not(:hover) .sidebar-link:hover .sidebar-tooltip {
            display: block;
        }

        /* Main content transition */
        .main-content {
            margin-left: 80px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar:hover ~ .main-wrapper .main-content {
            margin-left: 260px;
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .table-row {
            transition: background 0.2s ease;
        }

        .table-row:hover {
            background: #F5F1EA;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease forwards;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #F5F1EA;
        }

        ::-webkit-scrollbar-thumb {
            background: #2D5A3D;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1E3D2A;
        }

        /* Dark scrollbar for sidebar */
        .sidebar nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.15);
        }

        .sidebar nav::-webkit-scrollbar {
            width: 4px;
        }
    </style>
    
    @stack('styles')
</head>

<body class="bg-sand min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-gradient-to-b from-forest to-forest-dark text-white fixed h-full z-30 transform -translate-x-full lg:translate-x-0 transition-all duration-300 overflow-hidden">
            <!-- Logo -->
            <div class="p-5 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                        <img src="{{ asset('uploads/logo.png') }}" alt="Gamartisan" class="w-9 h-9 object-contain">
                    </div>
                    <div class="sidebar-logo-text">
                        <h1 class="font-bold text-lg leading-tight">Gamartisan</h1>
                        <p class="text-xs text-white/60">Admin Panel</p>
                    </div>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="p-3 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 160px)">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="sidebar-text">Dashboard</span>
                    <span class="sidebar-tooltip">Dashboard</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="sidebar-section-label px-3 text-xs font-semibold text-white/40 uppercase tracking-wider">Users</p>
                </div>

                <a href="{{ route('admin.buyers.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('admin.buyers*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="sidebar-text">Buyers</span>
                    <span class="sidebar-tooltip">Buyers</span>
                </a>

                <a href="{{ route('admin.artists.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('admin.artists*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="sidebar-text">Artists</span>
                    <span class="sidebar-tooltip">Artists</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="sidebar-section-label px-3 text-xs font-semibold text-white/40 uppercase tracking-wider">Shop</p>
                </div>

                <a href="{{ route('admin.products.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="sidebar-text">Products</span>
                    <span class="sidebar-tooltip">Products</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span class="sidebar-text">Orders</span>
                    <span class="sidebar-tooltip">Orders</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="sidebar-text">Categories</span>
                    <span class="sidebar-tooltip">Categories</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="sidebar-section-label px-3 text-xs font-semibold text-white/40 uppercase tracking-wider">Charity</p>
                </div>

                <a href="{{ route('admin.charities.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('admin.charities.index', 'admin.charities.show', 'admin.charities.edit', 'admin.charities.create') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="sidebar-text">Charities</span>
                    <span class="sidebar-tooltip">Charities</span>
                </a>

                <a href="{{ route('admin.charities.summary') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('admin.charities.summary') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="sidebar-text">Donation Summary</span>
                    <span class="sidebar-tooltip">Donation Summary</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="sidebar-text">Reports</span>
                    <span class="sidebar-tooltip">Reports</span>
                </a>

                <a href="{{ route('admin.reviews.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    <span class="sidebar-text">Reviews</span>
                    <span class="sidebar-tooltip">Reviews</span>
                </a>
            </nav>

            <!-- Logout -->
            <div class="absolute bottom-0 left-0 right-0 p-3">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg w-full text-left text-red-300 hover:text-red-200">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
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
                <header class="bg-white shadow-sm sticky top-0 z-20">
                    <div class="flex items-center justify-between px-6 py-4">
                        <!-- Mobile Menu Button -->
                        <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg hover:bg-sand">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <div class="flex-1 lg:flex-none">
                            <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        </div>

                        <div class="flex items-center gap-4">

                            @include('partials.notification-bell')
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-forest rounded-full flex items-center justify-center text-white text-sm font-medium">
                                    A
                                </div>
                                <span class="text-sm text-gray-600 hidden sm:inline">Admin</span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <div class="p-6">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 fade-in">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2 fade-in">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden"></div>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    </script>
    
    @stack('scripts')
</body>

</html>
