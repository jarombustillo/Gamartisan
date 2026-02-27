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

                <!-- Buy / Cart Buttons -->
                @if(session('user_type') === 'buyer' || session('user_type') === 'artist')
                    <div class="flex gap-3">
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full text-center border-2 border-forest text-forest py-4 rounded-xl font-semibold text-lg hover:bg-forest/5 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                                Add to Cart
                            </button>
                        </form>
                        <a href="{{ route('checkout', $product) }}" class="flex-1 text-center bg-forest text-white py-4 rounded-xl font-semibold text-lg hover:bg-forest-dark transition-colors shadow-lg hover:shadow-xl">
                            Buy Now
                        </a>
                    </div>
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

        <!-- Reviews Section -->
        <div class="mt-16">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="font-display text-2xl font-bold text-forest-dark">Customer Reviews</h2>
                    <div class="flex items-center gap-3 mt-2">
                        @if($reviewCount > 0)
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-lg font-semibold text-gray-800">{{ number_format($avgRating, 1) }}</span>
                            <span class="text-gray-500">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                        @else
                            <span class="text-gray-500">No reviews yet</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            @if($reviewableOrders->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
                    <h3 class="font-semibold text-gray-800 mb-4">Write a Review</h3>
                    <form action="{{ route('reviews.store', $product) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                            <select name="orderID" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none">
                                @foreach($reviewableOrders as $order)
                                    <option value="{{ $order->orderID }}">Order #{{ $order->orderID }} — {{ $order->orderDate->format('M d, Y') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                            <div class="flex gap-1" id="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="revRating" value="{{ $i }}" class="hidden" {{ $i === 5 ? 'checked' : '' }}>
                                        <svg class="w-8 h-8 star-icon {{ $i <= 5 ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Review (optional)</label>
                            <textarea name="revText" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none resize-none" placeholder="Share your experience..."></textarea>
                        </div>
                        <button type="submit" class="bg-forest text-white px-6 py-2.5 rounded-lg font-medium hover:bg-forest-dark transition-colors">
                            Submit Review
                        </button>
                    </form>
                </div>
            @endif

            <!-- Review List -->
            <div class="space-y-4">
                @forelse($reviews as $review)
                    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-forest/10 rounded-full flex items-center justify-center">
                                    <span class="text-forest font-semibold">{{ strtoupper(substr($review->buyer->firstName ?? 'B', 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $review->buyer->firstName ?? 'Buyer' }} {{ $review->buyer->lastName ?? '' }}</p>
                                    <div class="flex items-center gap-2">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->revRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $review->datePosted ? $review->datePosted->diffForHumans() : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($review->revText)
                            <p class="mt-3 text-gray-600 leading-relaxed">{{ $review->revText }}</p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-xl border border-gray-100">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        <p class="text-gray-500">No reviews yet. Be the first to share your experience!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Interactive star rating
    document.querySelectorAll('#star-rating label').forEach((label, index) => {
        label.addEventListener('click', () => {
            const stars = document.querySelectorAll('#star-rating .star-icon');
            stars.forEach((star, i) => {
                star.classList.toggle('text-yellow-400', i <= index);
                star.classList.toggle('text-gray-300', i > index);
            });
        });
    });
</script>
@endpush
@endsection
