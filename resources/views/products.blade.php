@extends('layouts.app')

@section('title', 'Products - Gamartisan')

@section('content')
<div class="pt-24 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-display font-bold text-forest-dark">Our Products</h1>
                <p class="text-gray-600 mt-1">Handcrafted with love by our talented artisans</p>
            </div>
            
            <!-- Search -->
            <form action="{{ route('products') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                    class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none w-64">
                <button type="submit" class="px-4 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors">
                    Search
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-64 flex-shrink-0">
                <form action="{{ route('products') }}" method="GET" class="bg-white rounded-xl shadow-sm p-6 sticky top-28">
                    <h3 class="font-semibold text-gray-800 mb-4">Filters</h3>
                    
                    <!-- Categories -->
                    <div class="mb-6">
                        <label class="text-sm font-medium text-gray-700 mb-2 block">Category</label>
                        <select name="category" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->categoryID }}" {{ request('category') == $category->categoryID ? 'selected' : '' }}>
                                    {{ $category->catName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="text-sm font-medium text-gray-700 mb-2 block">Price Range</label>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" 
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="mb-6">
                        <label class="text-sm font-medium text-gray-700 mb-2 block">Sort By</label>
                        <select name="sort" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-forest text-white py-2 rounded-lg hover:bg-forest-dark transition-colors">
                        Apply Filters
                    </button>
                    
                    @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']))
                        <a href="{{ route('products') }}" class="block text-center text-gray-500 hover:text-gray-700 mt-2 text-sm">
                            Clear All
                        </a>
                    @endif
                </form>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                @if($products->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">No products found</p>
                        <p class="text-gray-400 mt-2">Try adjusting your filters or search terms</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="product-card bg-white rounded-xl shadow-sm overflow-hidden group">
                                <a href="{{ route('products.show', $product) }}" class="block">
                                    @if($product->prodImage)
                                        <div class="relative overflow-hidden">
                                            <img src="{{ asset($product->prodImage) }}" alt="{{ $product->prodName }}" 
                                                class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                        </div>
                                    @else
                                        <div class="w-full h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </a>
                                
                                <div class="p-5">
                                    <div class="flex items-start justify-between mb-2">
                                        <span class="text-xs font-medium text-forest bg-forest/10 px-2 py-1 rounded">
                                            {{ $product->category->catName ?? 'Uncategorized' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('products.show', $product) }}">
                                        <h3 class="font-semibold text-gray-800 mb-1 group-hover:text-forest transition-colors">
                                            {{ $product->prodName }}
                                        </h3>
                                    </a>
                                    <p class="text-sm text-gray-500 mb-3">
                                        by {{ $product->artist->fullName ?? 'Unknown Artist' }}
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-bold text-forest">₱{{ number_format($product->prodPrice, 2) }}</span>
                                        @if(session('user_type') === 'buyer' || session('user_type') === 'artist')
                                            <button type="button" class="add-to-cart-btn px-4 py-2 bg-forest text-white text-sm rounded-lg hover:bg-forest-dark transition-colors flex items-center gap-1"
                                                data-url="{{ route('cart.add', $product) }}" data-token="{{ csrf_token() }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                                                Add
                                            </button>
                                        @else
                                            <a href="{{ route('products.show', $product) }}" class="px-4 py-2 border border-forest text-forest text-sm rounded-lg hover:bg-forest hover:text-white transition-colors">
                                                View Details
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Toast notification -->
<div id="cart-toast" class="fixed bottom-6 right-6 bg-forest text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 transform translate-y-20 opacity-0 transition-all duration-300 z-50" style="pointer-events: none;">
    <svg class="w-5 h-5 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    <span id="cart-toast-msg">Added to cart!</span>
</div>

<script>
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.dataset.url;
        const token = this.dataset.token;
        const button = this;

        // Disable button briefly
        button.disabled = true;
        button.style.opacity = '0.6';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update cart badge
                document.querySelectorAll('.cart-badge').forEach(badge => {
                    badge.textContent = data.cartCount;
                    badge.classList.remove('hidden');
                });
                // Also update any badge that was hidden
                const cartLink = document.querySelector('a[href*="/cart"]');
                if (cartLink) {
                    let badge = cartLink.querySelector('.cart-badge');
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.className = 'cart-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold';
                        cartLink.appendChild(badge);
                    }
                    badge.textContent = data.cartCount;
                    badge.classList.remove('hidden');
                }

                // Show toast
                const toast = document.getElementById('cart-toast');
                document.getElementById('cart-toast-msg').textContent = data.message;
                toast.style.transform = 'translateY(0)';
                toast.style.opacity = '1';
                setTimeout(() => {
                    toast.style.transform = 'translateY(20px)';
                    toast.style.opacity = '0';
                }, 2500);
            }
        })
        .catch(() => {
            // Fallback: submit as form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = `<input type="hidden" name="_token" value="${token}">`;
            document.body.appendChild(form);
            form.submit();
        })
        .finally(() => {
            setTimeout(() => {
                button.disabled = false;
                button.style.opacity = '1';
            }, 500);
        });
    });
});
</script>
@endsection
