@extends('admin.layouts.app')

@section('title', 'Sales Report')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Sales Report</h1>
        <p class="text-gray-500 mt-1">
            @if($dateFrom && $dateTo)
                {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
            @elseif($dateFrom)
                From {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }}
            @elseif($dateTo)
                Up to {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
            @else
                All time
            @endif
        </p>
    </div>
    <div class="flex gap-2">
        <button onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print
        </button>
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            ← Back
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-sm text-gray-500">Total Orders</p>
        <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-sm text-gray-500">Total Revenue</p>
        <p class="text-2xl font-bold text-green-600">₱{{ number_format($totalRevenue, 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-sm text-gray-500">Platform Fees (5%)</p>
        <p class="text-2xl font-bold text-forest">₱{{ number_format($totalPlatformFee, 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-sm text-gray-500">Seller Earnings</p>
        <p class="text-2xl font-bold text-ochre">₱{{ number_format($totalSellerEarnings, 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-sm text-gray-500">Avg Order Value</p>
        <p class="text-2xl font-bold text-gray-800">₱{{ number_format($avgOrderValue, 2) }}</p>
    </div>
</div>

<!-- Status Breakdown -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h2 class="font-semibold text-gray-800 mb-4">Order Status Breakdown</h2>
    <div class="flex flex-wrap gap-4">
        @foreach($statusBreakdown as $status => $count)
            <div class="flex items-center gap-2 px-4 py-2 rounded-full
                {{ $status === 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                {{ $status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $status === 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                {{ $status === 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                {{ $status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
            ">
                <span class="font-semibold">{{ $count }}</span>
                <span class="capitalize">{{ $status }}</span>
            </div>
        @endforeach
    </div>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Platform Fee</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">#{{ $order->orderID }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $order->product->prodName ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $order->buyer->firstName ?? '' }} {{ $order->buyer->lastName ?? '' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $order->ordQuantity }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">₱{{ number_format($order->ordTotalPrice, 2) }}</td>
                        <td class="px-6 py-4 text-forest">₱{{ number_format($order->platformFee, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium capitalize
                                {{ $order->ordStatus === 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $order->ordStatus === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $order->ordStatus === 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $order->ordStatus === 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $order->ordStatus === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                            ">{{ $order->ordStatus }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $order->orderDate ? \Carbon\Carbon::parse($order->orderDate)->format('M d, Y') : '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">No orders found for the selected period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
