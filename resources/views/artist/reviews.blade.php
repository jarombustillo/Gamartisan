@extends('artist.layouts.app')

@section('page-title', 'Product Reviews')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Product Reviews</h1>
        <p class="text-gray-500 mt-1">Reviews from customers on your products</p>
    </div>

    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-ochre/20 rounded-full flex items-center justify-center">
                            <span class="text-ochre font-semibold">{{ strtoupper(substr($review->buyer->firstName ?? 'B', 0, 1)) }}</span>
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
                    <span class="text-sm font-medium text-ochre bg-ochre/10 px-3 py-1 rounded-full">{{ $review->product->prodName ?? '' }}</span>
                </div>
                @if($review->revText)
                    <p class="mt-3 text-gray-600 leading-relaxed">{{ $review->revText }}</p>
                @endif
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-xl border border-gray-100">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <p class="text-gray-500 text-lg">No reviews yet</p>
                <p class="text-gray-400 text-sm mt-1">Reviews will appear here when customers review your products</p>
            </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
        <div class="flex justify-center">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
