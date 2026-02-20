@extends('admin.layouts.app')
@section('title', $category->catName)
@section('page-title', $category->catName)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Categories
        </a>
        <a href="{{ route('admin.categories.edit', $category) }}" class="px-4 py-2 bg-ochre hover:bg-ochre/90 text-white rounded-lg">Edit</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-forest/10 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $category->catName }}</h2>
                <p class="text-gray-500">{{ $category->products->count() }} products in this category</p>
            </div>
        </div>

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Products</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($category->products as $product)
                <a href="{{ route('admin.products.show', $product) }}" class="border border-gray-100 rounded-lg p-4 hover:shadow-sm transition-shadow block">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-medium text-gray-800">{{ $product->prodName }}</h4>
                            <p class="text-sm text-gray-500">{{ $product->artist->fullName ?? 'Unknown' }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $product->prodStatus === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($product->prodStatus) }}
                        </span>
                    </div>
                    <p class="text-lg font-semibold text-forest mt-2">₱{{ number_format($product->prodPrice, 2) }}</p>
                </a>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500">No products in this category</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
