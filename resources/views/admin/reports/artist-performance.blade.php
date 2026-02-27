@extends('admin.layouts.app')

@section('title', 'Artist Performance Report')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Artist Performance Report</h1>
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

<!-- Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
        <p class="text-sm text-gray-500">Total Artists</p>
        <p class="text-3xl font-bold text-ochre">{{ $artists->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
        <p class="text-sm text-gray-500">Total Sales</p>
        <p class="text-3xl font-bold text-green-600">₱{{ number_format($grandTotalSales, 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
        <p class="text-sm text-gray-500">Total Orders</p>
        <p class="text-3xl font-bold text-gray-800">{{ $grandTotalOrders }}</p>
    </div>
</div>

<!-- Artist Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Rank</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Artist</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Products</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Items Sold</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total Sales</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Earnings (85%)</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Avg Order</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($artists as $index => $artist)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if($index === 0)
                                <span class="w-7 h-7 bg-yellow-100 text-yellow-700 rounded-full flex items-center justify-center font-bold text-sm">🥇</span>
                            @elseif($index === 1)
                                <span class="w-7 h-7 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center font-bold text-sm">🥈</span>
                            @elseif($index === 2)
                                <span class="w-7 h-7 bg-orange-100 text-orange-700 rounded-full flex items-center justify-center font-bold text-sm">🥉</span>
                            @else
                                <span class="text-gray-400 font-medium">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $artist->artist->fullName ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $artist->artist->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $artist->product_count }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $artist->total_orders }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $artist->total_items_sold }}</td>
                        <td class="px-6 py-4 font-semibold text-green-600">₱{{ number_format($artist->total_sales, 2) }}</td>
                        <td class="px-6 py-4 font-semibold text-ochre">₱{{ number_format($artist->total_earnings, 2) }}</td>
                        <td class="px-6 py-4 text-gray-600">₱{{ number_format($artist->avg_order_value, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">No artist data found for the selected period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
