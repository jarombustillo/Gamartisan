@extends('artist.layouts.app')

@section('title', 'My Products')
@section('page-title', 'My Products')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex gap-4">
            <form action="{{ route('artist.products') }}" method="GET" class="flex gap-2">
                <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-ochre text-white rounded-lg hover:bg-ochre/90">Filter</button>
            </form>
        </div>
        <a href="{{ route('artist.products.create') }}" class="inline-flex items-center gap-2 bg-ochre hover:bg-ochre/90 text-white px-4 py-2 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Product
        </a>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden group">
                @if($product->prodImage)
                    <img src="{{ asset($product->prodImage) }}" alt="{{ $product->prodName }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-800">{{ Str::limit($product->prodName, 25) }}</h3>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $product->prodStatus === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($product->prodStatus) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">{{ $product->category->catName ?? 'No Category' }}</p>
                    <p class="text-lg font-bold text-ochre mb-4">₱{{ number_format($product->prodPrice, 2) }}</p>
                    
                    <div class="flex items-center gap-2">
                        <a href="{{ route('artist.products.edit', $product) }}" class="flex-1 text-center py-2 border border-gray-200 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700">
                            Edit
                        </a>
                        <form action="{{ route('artist.products.destroy', $product) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full py-2 border border-red-200 rounded-lg hover:bg-red-50 text-sm font-medium text-red-600">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <p class="text-gray-500 mb-4">You haven't added any products yet</p>
                <a href="{{ route('artist.products.create') }}" class="inline-flex items-center gap-2 bg-ochre hover:bg-ochre/90 text-white px-6 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Your First Product
                </a>
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="mt-6">{{ $products->links() }}</div>
    @endif
</div>
@endsection
