@extends('admin.layouts.app')

@section('title', 'Donation Report')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Donation Report</h1>
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
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
        <p class="text-sm text-gray-500">Total Donations</p>
        <p class="text-3xl font-bold text-pink-600">₱{{ number_format($totalDonated, 2) }}</p>
        <p class="text-gray-400 text-sm mt-1">{{ $totalDonations }} transactions</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
        <p class="text-sm text-gray-500">Charities Supported</p>
        <p class="text-3xl font-bold text-forest">{{ $byCharity->count() }}</p>
    </div>
</div>

<!-- By Charity Breakdown -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h2 class="font-semibold text-gray-800 mb-4">Donations by Charity</h2>
    <div class="space-y-3">
        @foreach($byCharity as $charityName => $data)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $charityName }}</p>
                        <p class="text-sm text-gray-500">{{ $data['count'] }} donations</p>
                    </div>
                </div>
                <span class="text-lg font-bold text-pink-600">₱{{ number_format($data['total'], 2) }}</span>
            </div>
        @endforeach
    </div>
</div>

<!-- Donations Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Charity</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Buyer</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($donations as $donation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">#{{ $donation->orderID }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $donation->charity->charName ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $donation->order->buyer->firstName ?? '' }} {{ $donation->order->buyer->lastName ?? '' }}</td>
                        <td class="px-6 py-4 font-semibold text-pink-600">₱{{ number_format($donation->amountDonated, 2) }}</td>
                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $donation->dateDonated ? \Carbon\Carbon::parse($donation->dateDonated)->format('M d, Y') : '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">No donations found for the selected period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
