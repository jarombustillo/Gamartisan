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
                    <a href="{{ route('products') }}" class="btn-primary inline-flex items-center px-8 py-4 text-cream font-semibold rounded-full shadow-lg">
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
