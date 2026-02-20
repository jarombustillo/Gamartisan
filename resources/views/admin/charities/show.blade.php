@extends('admin.layouts.app')
@section('title', $charity->charName)
@section('page-title', $charity->charName)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.charities.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Charities
        </a>
        <a href="{{ route('admin.charities.edit', $charity) }}" class="px-4 py-2 bg-ochre hover:bg-ochre/90 text-white rounded-lg">Edit</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-start gap-4 mb-6">
            <div class="w-16 h-16 bg-pink-100 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $charity->charName }}</h2>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $charity->charStatus === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($charity->charStatus) }}
                    </span>
                </div>
                @if($charity->charDesc)
                    <p class="text-gray-600 mb-3">{{ $charity->charDesc }}</p>
                @endif
                @if($charity->charConDetails)
                    <p class="text-sm text-gray-500"><strong>Contact:</strong> {{ $charity->charConDetails }}</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-pink-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Total Donations</p>
                <p class="text-2xl font-bold text-pink-600">₱{{ number_format($totalDonations, 2) }}</p>
            </div>
            <div class="bg-sand rounded-lg p-4">
                <p class="text-sm text-gray-500">Number of Donations</p>
                <p class="text-2xl font-bold text-gray-800">{{ $charity->donations->count() }}</p>
            </div>
        </div>

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Donation History</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-3">Order ID</th>
                        <th class="px-4 py-3">Donor</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($charity->donations as $donation)
                        <tr class="table-row">
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">#{{ $donation->orderID }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $donation->order->buyer->fullName ?? 'Anonymous' }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-pink-600">₱{{ number_format($donation->amountDonated, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $donation->dateDonated ? $donation->dateDonated->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No donations yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
