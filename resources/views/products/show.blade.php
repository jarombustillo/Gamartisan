@extends('layouts.app')

@section('title', $product->prodName . ' - Gamartisan')

@section('content')
<div class="pt-24 min-h-screen">
    <div class="max-w-6xl mx-auto px-6 py-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-forest">Home</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <a href="{{ route('products') }}" class="hover:text-forest">Products</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-gray-800">{{ $product->prodName }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div>
                @if($product->prodImage)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset($product->prodImage) }}" alt="{{ $product->prodName }}" class="w-full h-[500px] object-cover">
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-lg h-[500px] flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                @if($product->category)
                    <span class="inline-block text-xs font-medium text-forest bg-forest/10 px-3 py-1 rounded-full">
                        {{ $product->category->catName }}
                    </span>
                @endif

                <h1 class="font-display text-3xl lg:text-4xl font-bold text-forest-dark">
                    {{ $product->prodName }}
                </h1>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-ochre/20 rounded-full flex items-center justify-center">
                        <span class="text-ochre font-semibold">{{ strtoupper(substr($product->artist->firstName ?? 'A', 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Handcrafted by</p>
                        <p class="font-medium text-gray-800">{{ $product->artist->fullName ?? 'Unknown Artist' }}</p>
                    </div>
                </div>

                @if($product->prodDesc)
                    <div class="border-t border-b border-gray-200 py-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Description</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $product->prodDesc }}</p>
                    </div>
                @endif

                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-forest">₱{{ number_format($product->prodPrice, 2) }}</span>
                </div>

                <!-- Charity Note -->
                <div class="bg-pink-50 border border-pink-200 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-6 h-6 text-pink-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-pink-700">10% goes to charity</p>
                        <p class="text-sm text-pink-600">₱{{ number_format($product->prodPrice * 0.10, 2) }} of each unit will be donated to support a charitable cause.</p>
                    </div>
                </div>

                <!-- Buy Button -->
                @if(session('user_type') === 'buyer' || session('user_type') === 'artist')
                    <a href="{{ route('checkout', $product) }}" class="block w-full text-center bg-forest text-white py-4 rounded-xl font-semibold text-lg hover:bg-forest-dark transition-colors shadow-lg hover:shadow-xl">
                        Buy Now
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center border-2 border-forest text-forest py-4 rounded-xl font-semibold text-lg hover:bg-forest hover:text-white transition-colors">
                        Login to Purchase
                    </a>
                @endif

                <!-- Status -->
                <div class="flex items-center gap-2 text-sm">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    <span class="text-gray-600">Available</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
