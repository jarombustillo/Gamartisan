@extends('layouts.app')

@section('title', 'Gamartisan - Crafted to Give. Made to Matter.')

@section('content')
<!-- Hero Section -->
<section id="home" class="hero-gradient min-h-screen pt-24 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 py-12 lg:py-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Hero Content -->
            <div class="space-y-6">
                <h1 class="font-display text-5xl lg:text-6xl xl:text-7xl font-bold text-forest-dark leading-tight">
                    Crafted to Give.<br>
                    <span class="text-forest">Made to Matter.</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-md leading-relaxed">
                    Your crafts aren't just beautiful — they're meaningful. Every purchase has purpose, and every
                    piece supports someone's story.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#products" class="btn-primary inline-flex items-center px-8 py-4 text-cream font-semibold rounded-full shadow-lg">
                        Explore Creations
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <!-- Social Icons -->
                <div class="flex items-center space-x-4 pt-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center hover:shadow-lg transition-shadow">
                        <svg class="w-5 h-5 text-forest-dark" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z" />
                            <circle cx="12" cy="12" r="3.5" />
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center hover:shadow-lg transition-shadow">
                        <svg class="w-5 h-5 text-forest-dark" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center hover:shadow-lg transition-shadow">
                        <svg class="w-5 h-5 text-forest-dark" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Hero Illustration -->
            <div class="relative flex justify-center lg:justify-end">
                <div class="relative float-animation">
                    <div class="w-80 h-96 lg:w-96 lg:h-[500px] bg-gradient-to-br from-sage/30 to-forest/20 rounded-3xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=600&fit=crop" alt="Artisan wearing handcrafted clothing" class="w-full h-full object-cover">
                    </div>
                    <!-- Decorative Elements -->
                    <div class="absolute -top-4 -left-4 w-16 h-16 bg-ochre/30 rounded-full blur-xl"></div>
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-sage/40 rounded-full blur-xl"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section id="products" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Sidebar Navigation -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="sticky top-28 bg-cream/50 rounded-2xl p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Navigation</h3>
                    <div class="relative mb-6">
                        <input type="text" placeholder="Search items..." class="w-full px-4 py-3 bg-white rounded-xl border border-gray-200 focus:border-forest focus:ring-2 focus:ring-forest/20 outline-none transition-all text-sm">
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <nav class="space-y-3">
                        <a href="#" class="sidebar-link flex items-center space-x-3 text-gray-600 hover:text-forest-dark">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profile</span>
                        </a>
                        <a href="#" class="sidebar-link flex items-center space-x-3 text-gray-600 hover:text-forest-dark">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span>Wishlist</span>
                        </a>
                        <a href="#" class="sidebar-link flex items-center space-x-3 text-gray-600 hover:text-forest-dark">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Categories</span>
                        </a>
                        <a href="#" class="sidebar-link flex items-center space-x-3 text-gray-600 hover:text-forest-dark">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Settings</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Product Grid -->
            <div class="flex-1">
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Product Card 1 -->
                    <div class="product-card bg-cream rounded-2xl overflow-hidden shadow-lg cursor-pointer">
                        <div class="aspect-square overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?w=400&h=400&fit=crop" alt="Digital Art" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-forest-dark">Digital Art</h4>
                            <p class="text-forest font-bold text-lg">₱299.99</p>
                        </div>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="product-card bg-cream rounded-2xl overflow-hidden shadow-lg cursor-pointer">
                        <div class="aspect-square overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1511367461989-f85a21fda167?w=400&h=400&fit=crop" alt="Dream Catcher" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-forest-dark">Dream Catcher</h4>
                            <p class="text-forest font-bold text-lg">₱799.99</p>
                        </div>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="product-card bg-cream rounded-2xl overflow-hidden shadow-lg cursor-pointer">
                        <div class="aspect-square overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1544967082-d9d25d867d66?w=400&h=400&fit=crop" alt="Shark Tooth Necklace" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-forest-dark">Shark Tooth Necklace</h4>
                            <p class="text-forest font-bold text-lg">₱200.00</p>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="product-card bg-cream rounded-2xl overflow-hidden shadow-lg cursor-pointer">
                        <div class="aspect-square overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1596568362785-2e7a77ec88cc?w=400&h=400&fit=crop" alt="Surfing the Waves Painting" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-forest-dark">Surfing the Waves of Siargao Painting</h4>
                            <p class="text-forest font-bold text-lg">₱1,000.00</p>
                        </div>
                    </div>

                    <!-- Product Card 5 -->
                    <div class="product-card bg-cream rounded-2xl overflow-hidden shadow-lg cursor-pointer">
                        <div class="aspect-square overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1502823403499-6ccfcf4fb453?w=400&h=400&fit=crop" alt="Handmade Basket Painting" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-forest-dark">Handmade Basket Painting</h4>
                            <p class="text-forest font-bold text-lg">₱1,200.00</p>
                        </div>
                    </div>

                    <!-- Product Card 6 -->
                    <div class="product-card bg-cream rounded-2xl overflow-hidden shadow-lg cursor-pointer">
                        <div class="aspect-square overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1490312278390-ab64016e0aa9?w=400&h=400&fit=crop" alt="Mountain Sunset Art" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-forest-dark">Mountain Sunset Art</h4>
                            <p class="text-forest font-bold text-lg">₱850.00</p>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-center space-x-2 mt-12">
                    <button class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-forest hover:text-cream hover:border-forest transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-forest text-cream font-semibold">1</button>
                    <button class="w-10 h-10 rounded-full border border-gray-300 hover:bg-forest hover:text-cream hover:border-forest transition-all font-semibold">2</button>
                    <button class="w-10 h-10 rounded-full border border-gray-300 hover:bg-forest hover:text-cream hover:border-forest transition-all font-semibold">3</button>
                    <button class="w-10 h-10 rounded-full border border-gray-300 hover:bg-forest hover:text-cream hover:border-forest transition-all font-semibold">4</button>
                    <button class="w-10 h-10 rounded-full border border-gray-300 hover:bg-forest hover:text-cream hover:border-forest transition-all font-semibold">5</button>
                    <span class="px-2 text-gray-400">...</span>
                    <button class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-forest hover:text-cream hover:border-forest transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quote Section -->
<section class="relative h-[500px]">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=1920&h=600&fit=crop" alt="Children in community" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent"></div>
    </div>
    <div class="relative h-full max-w-7xl mx-auto px-6 flex items-center">
        <div class="max-w-xl">
            <h2 class="font-display text-4xl lg:text-5xl text-white font-bold leading-tight">
                Bright souls growing in places where support runs thin.
            </h2>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-20 bg-cream">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="font-display text-3xl lg:text-4xl font-bold text-forest-dark mb-8">
            Gamartisan is a haven for handmade stories.
        </h2>
        <div class="space-y-6 text-gray-600 leading-relaxed">
            <p>
                A place where artisans share their craft, and every piece holds the warmth of the hands that created
                it. We believe that creativity can spark kindness — so when you bring home something crafted with
                heart, each order carries a gift forward to someone in need.
            </p>
            <p>
                Here, you're not just supporting a maker.<br>
                You're helping turn every handmade piece into a quiet act of charity.
            </p>
            <div class="pt-4 border-t border-gray-200">
                <p class="italic text-forest-dark font-medium">Crafted with heart.</p>
                <p class="italic text-forest-dark font-medium">Shared with love.</p>
                <p class="italic text-forest-dark font-medium">Given with purpose.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="font-display text-4xl lg:text-5xl text-white font-bold leading-tight mb-6">
                    Every Order Tells a<br>Story.
                </h2>
                <a href="#products" class="inline-flex items-center px-8 py-4 bg-cream text-forest-dark font-semibold rounded-full hover:bg-white transition-all shadow-lg hover:shadow-xl">
                    Place Order
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
            <div class="text-cream/90">
                <p class="text-lg leading-relaxed">
                    Behind every handmade piece is a journey — of creativity, culture, and compassion.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
