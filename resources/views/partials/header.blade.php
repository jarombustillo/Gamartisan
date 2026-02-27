<!-- Navigation Header -->
<header class="fixed top-0 left-0 right-0 z-50 bg-cream/95 backdrop-blur-sm border-b border-sand">
    <nav class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('uploads/logo.png') }}" alt="Gamartisan Logo" class="h-12 w-auto object-contain">
                    <span class="font-display text-2xl font-semibold text-forest-dark">Gamartisan</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}#about" class="nav-link text-gray-600 hover:text-forest-dark font-medium">About</a>

                @if(session('user_type') === 'buyer')
                    <!-- Logged in as Buyer -->
                    <a href="{{ route('products') }}" class="nav-link text-gray-600 hover:text-forest-dark font-medium">Products</a>
                    <a href="{{ route('buyer.orders') }}" class="nav-link text-gray-600 hover:text-forest-dark font-medium">My Orders</a>
                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-forest-dark transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                        @php $cartCount = \App\Models\CartItem::where('userID', session('user_id'))->where('userType', 'buyer')->count(); @endphp
                        @if($cartCount > 0)
                            <span class="cart-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-forest font-medium">
                            <span class="w-8 h-8 bg-forest/10 rounded-full flex items-center justify-center">
                                {{ strtoupper(substr(session('user_name'), 0, 1)) }}
                            </span>
                            {{ session('user_name') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <a href="{{ route('buyer.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Profile</a>
                            <a href="{{ route('buyer.orders') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">My Orders</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-50">Logout</button>
                            </form>
                        </div>
                    </div>
                @elseif(session('user_type') === 'artist')
                    <!-- Logged in as Artist -->
                    <a href="{{ route('products') }}" class="nav-link text-gray-600 hover:text-forest-dark font-medium">Products</a>
                    <a href="{{ route('artist.dashboard') }}" class="nav-link text-ochre hover:text-ochre/80 font-medium">Artist Dashboard</a>
                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-ochre transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                        @php $cartCount = \App\Models\CartItem::where('userID', session('user_id'))->where('userType', 'artist')->count(); @endphp
                        @if($cartCount > 0)
                            <span class="cart-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-ochre font-medium">
                            <span class="w-8 h-8 bg-ochre/20 rounded-full flex items-center justify-center">
                                {{ strtoupper(substr(session('user_name'), 0, 1)) }}
                            </span>
                            {{ session('user_name') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <a href="{{ route('artist.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Dashboard</a>
                            <a href="{{ route('artist.products') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">My Products</a>
                            <a href="{{ route('artist.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Profile</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-50">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Not logged in -->
                    <a href="{{ route('login') }}" class="nav-link text-gray-600 hover:text-forest-dark font-medium">Login</a>
                    <a href="{{ route('join') }}" class="btn-primary text-white px-5 py-2 rounded-full font-medium">Register</a>
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <button id="menuBtn" class="md:hidden p-2 rounded-lg hover:bg-sand">
                <svg class="w-6 h-6 text-forest-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>
</header>
