@extends('admin.layouts.app')
@section('title', 'View Artist')
@section('page-title', 'Artist Details')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-ochre/20 rounded-full flex items-center justify-center">
                    <span class="text-ochre font-bold text-2xl">{{ strtoupper(substr($artist->firstName, 0, 1)) }}</span>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $artist->fullName }}</h2>
                    <p class="text-gray-500">{{ $artist->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.artists.edit', $artist) }}" class="px-4 py-2 bg-ochre hover:bg-ochre/90 text-white rounded-lg">Edit</a>
                <a href="{{ route('admin.artists.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Back</a>
            </div>
        </div>

        @if($artist->artBio)
            <div class="mb-6 p-4 bg-sand rounded-lg">
                <p class="text-sm text-gray-500 mb-1">Bio</p>
                <p class="text-gray-800">{{ $artist->artBio }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-sand rounded-lg p-4">
                <p class="text-sm text-gray-500">Artist ID</p>
                <p class="text-lg font-semibold text-gray-800">#{{ $artist->artistID }}</p>
            </div>
            <div class="bg-sand rounded-lg p-4">
                <p class="text-sm text-gray-500">Products</p>
                <p class="text-lg font-semibold text-gray-800">{{ $artist->products->count() }}</p>
            </div>
            <div class="bg-sand rounded-lg p-4">
                <p class="text-sm text-gray-500">Total Sales</p>
                <p class="text-lg font-semibold text-gray-800">₱{{ number_format($artist->orders->sum('ordTotalPrice'), 2) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Products</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($artist->products as $product)
                <div class="border border-gray-100 rounded-lg p-4 hover:shadow-sm transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-medium text-gray-800">{{ $product->prodName }}</h4>
                            <p class="text-sm text-gray-500">{{ $product->category->catName ?? 'No Category' }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $product->prodStatus === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($product->prodStatus) }}
                        </span>
                    </div>
                    <p class="text-lg font-semibold text-forest mt-2">₱{{ number_format($product->prodPrice, 2) }}</p>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500">No products yet</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
