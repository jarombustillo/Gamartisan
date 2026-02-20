@extends('artist.layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('artist.orders') }}" method="GET" class="flex gap-4">
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-ochre text-white rounded-lg hover:bg-ochre/90">Filter</button>
            @if(request('status'))
                <a href="{{ route('artist.orders') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Buyer</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">#{{ $order->orderID }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->product->prodName ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->buyer->fullName ?? 'N/A' }}</td>
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
                            <td class="px-6 py-4">
                                <form action="{{ route('artist.orders.status', $order) }}" method="POST" class="flex gap-2">
                                    @csrf @method('PUT')
                                    <select name="ordStatus" class="text-sm px-2 py-1 border border-gray-200 rounded focus:ring-1 focus:ring-ochre outline-none">
                                        <option value="pending" {{ $order->ordStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->ordStatus === 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ $order->ordStatus === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->ordStatus === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $order->ordStatus === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <button type="submit" class="px-3 py-1 bg-ochre text-white text-sm rounded hover:bg-ochre/90">Update</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <p>No orders yet</p>
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
