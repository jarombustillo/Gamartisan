@extends('admin.layouts.app')

@section('title', 'Products')
@section('page-title', 'Products Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-gray-600">Manage all products</p>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 bg-forest hover:bg-forest-dark text-white px-4 py-2 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Product
        </a>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                <option value="">All Status</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>
            <select name="category" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->categoryID }}" {{ request('category') == $category->categoryID ? 'selected' : '' }}>{{ $category->catName }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors">Filter</button>
            @if(request()->hasAny(['search', 'status', 'category']))
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Artist</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($product->prodImage)
                                        <img src="{{ asset($product->prodImage) }}" alt="{{ $product->prodName }}" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ Str::limit($product->prodName, 30) }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $product->productID }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $product->artist->fullName ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $product->category->catName ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">₱{{ number_format($product->prodPrice, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $product->prodStatus === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($product->prodStatus) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.show', $product) }}" class="p-2 text-gray-400 hover:text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-gray-400 hover:text-ochre"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No products found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())<div class="px-6 py-4 border-t border-gray-100">{{ $products->links() }}</div>@endif
    </div>
</div>
@endsection
