@extends('admin.layouts.app')

@section('title', 'View Buyer')
@section('page-title', 'Buyer Details')

@section('content')
<div class="space-y-6">
    <!-- Buyer Info Card -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-forest/10 rounded-full flex items-center justify-center">
                    <span class="text-forest font-bold text-2xl">{{ strtoupper(substr($buyer->firstName, 0, 1)) }}</span>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $buyer->fullName }}</h2>
                    <p class="text-gray-500">{{ $buyer->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.buyers.edit', $buyer) }}" class="px-4 py-2 bg-ochre hover:bg-ochre/90 text-white rounded-lg transition-colors">
                    Edit
                </a>
                <a href="{{ route('admin.buyers.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-sand rounded-lg p-4">
                <p class="text-sm text-gray-500">Buyer ID</p>
                <p class="text-lg font-semibold text-gray-800">#{{ $buyer->buyerID }}</p>
            </div>
            <div class="bg-sand rounded-lg p-4">
                <p class="text-sm text-gray-500">Total Orders</p>
                <p class="text-lg font-semibold text-gray-800">{{ $buyer->orders->count() }}</p>
            </div>
            <div class="bg-sand rounded-lg p-4">
                <p class="text-sm text-gray-500">Total Spent</p>
                <p class="text-lg font-semibold text-gray-800">₱{{ number_format($buyer->orders->sum('ordTotalPrice'), 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order History</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-3">Order ID</th>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($buyer->orders as $order)
                        <tr class="table-row">
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">#{{ $order->orderID }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $order->product->prodName ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">₱{{ number_format($order->ordTotalPrice, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $order->orderDate ? $order->orderDate->format('M d, Y') : 'N/A' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'processing' => 'bg-blue-100 text-blue-700',
                                        'shipped' => 'bg-indigo-100 text-indigo-700',
                                        'delivered' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$order->ordStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($order->ordStatus) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">No orders yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Reviews -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Reviews</h3>
        <div class="space-y-4">
            @forelse($buyer->reviews as $review)
                <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->revRating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500">{{ $review->datePosted ? $review->datePosted->format('M d, Y') : '' }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-1">{{ $review->product->prodName ?? 'Product' }}</p>
                    <p class="text-gray-800">{{ $review->revText }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No reviews yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
