@extends('admin.layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders Management')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order ID or customer..." class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" placeholder="From Date">
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" placeholder="To Date">
            <button type="submit" class="px-6 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors">Filter</button>
            @if(request()->hasAny(['search', 'status', 'from_date', 'to_date']))
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="table-row">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">#{{ $order->orderID }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->buyer->fullName ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($order->product->prodName ?? 'N/A', 20) }}</td>
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
                                <a href="{{ route('admin.orders.show', $order) }}" class="p-2 text-gray-400 hover:text-blue-600 inline-block">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">No orders found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())<div class="px-6 py-4 border-t border-gray-100">{{ $orders->links() }}</div>@endif
    </div>
</div>
@endsection
