@extends('buyer.layouts.app')

@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('buyer.orders') }}" method="GET" class="flex gap-4">
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors">Filter</button>
            @if(request('status'))
                <a href="{{ route('buyer.orders') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <!-- Orders List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Artist</th>
                        <th class="px-6 py-4">Qty</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">#{{ $order->orderID }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($order->product && $order->product->prodImage)
                                        <img src="{{ asset($order->product->prodImage) }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg"></div>
                                    @endif
                                    <span class="text-sm text-gray-800">{{ $order->product->prodName ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->artist->fullName ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->ordQuantity }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">₱{{ number_format($order->ordTotalPrice, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->orderDate ? $order->orderDate->format('M d, Y') : 'N/A' }}</td>
                            <td class="px-6 py-4">
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <p class="mb-2">No orders found</p>
                                <a href="{{ route('products') }}" class="text-forest hover:text-forest-dark">Start Shopping →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
