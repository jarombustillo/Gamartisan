@extends('admin.layouts.app')

@section('page-title', 'Review Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Review Management</h1>
            <p class="text-gray-500 mt-1">Moderate and manage customer reviews</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Product or buyer name..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                <select name="rating" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors font-medium">Filter</button>
            @if(request('search') || request('rating'))
                <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Clear</a>
            @endif
        </form>
    </div>

    <!-- Reviews Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Product</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Buyer</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Rating</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Review</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-800">{{ $review->product->prodName ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600">{{ $review->buyer->firstName ?? '' }} {{ $review->buyer->lastName ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->revRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-600 text-sm max-w-xs truncate">{{ $review->revText ?? '—' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-500 text-sm">{{ $review->datePosted ? $review->datePosted->format('M d, Y') : '' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No reviews found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages())
        <div class="flex justify-center">
            {{ $reviews->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
