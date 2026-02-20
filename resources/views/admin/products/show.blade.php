@extends('admin.layouts.app')
@section('title', 'View Product')
@section('page-title', 'Product Details')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row gap-6">
            @if($product->prodImage)
                <img src="{{ asset($product->prodImage) }}" alt="{{ $product->prodName }}" class="w-full md:w-64 h-64 object-cover rounded-xl">
            @else
                <div class="w-full md:w-64 h-64 bg-gray-100 rounded-xl flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif
            <div class="flex-1">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">{{ $product->prodName }}</h2>
                        <p class="text-gray-500">by {{ $product->artist->fullName ?? 'Unknown Artist' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="px-4 py-2 bg-ochre hover:bg-ochre/90 text-white rounded-lg">Edit</a>
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Back</a>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div class="bg-sand rounded-lg p-3">
                        <p class="text-xs text-gray-500">Price</p>
                        <p class="text-lg font-semibold text-forest">₱{{ number_format($product->prodPrice, 2) }}</p>
                    </div>
                    <div class="bg-sand rounded-lg p-3">
                        <p class="text-xs text-gray-500">Category</p>
                        <p class="text-sm font-medium text-gray-800">{{ $product->category->catName ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-sand rounded-lg p-3">
                        <p class="text-xs text-gray-500">Status</p>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $product->prodStatus === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ ucfirst($product->prodStatus) }}</span>
                    </div>
                    <div class="bg-sand rounded-lg p-3">
                        <p class="text-xs text-gray-500">Orders</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $product->orders->count() }}</p>
                    </div>
                </div>
                @if($product->prodDesc)
                    <p class="text-gray-600">{{ $product->prodDesc }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Reviews ({{ $product->reviews->count() }})</h3>
        <div class="space-y-4">
            @forelse($product->reviews as $review)
                <div class="border-b border-gray-100 pb-4 last:border-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="font-medium text-gray-800">{{ $review->buyer->fullName ?? 'Anonymous' }}</span>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->revRating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500">{{ $review->datePosted ? $review->datePosted->format('M d, Y') : '' }}</span>
                    </div>
                    <p class="text-gray-600">{{ $review->revText }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No reviews yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
