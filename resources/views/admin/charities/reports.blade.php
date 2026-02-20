@extends('admin.layouts.app')
@section('title', 'Impact Reports')
@section('page-title', 'Charity Impact Reports')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="{{ route('admin.charities.reports') }}" method="GET" class="flex flex-wrap gap-4">
            <select name="charity" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                <option value="">All Charities</option>
                @foreach($charities as $charity)
                    <option value="{{ $charity->charityID }}" {{ request('charity') == $charity->charityID ? 'selected' : '' }}>
                        {{ $charity->charName }}
                    </option>
                @endforeach
            </select>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
            <button type="submit" class="px-6 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors">Filter</button>
            @if(request()->hasAny(['charity', 'from_date', 'to_date']))
                <a href="{{ route('admin.charities.reports') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-500">Filtered Total</p>
            <p class="text-2xl font-bold text-pink-600">₱{{ number_format($filteredTotal, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-500">Filtered Count</p>
            <p class="text-2xl font-bold text-gray-800">{{ $filteredCount }}</p>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Charity</th>
                        <th class="px-6 py-4">Donor</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($donations as $donation)
                        <tr class="table-row">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">#{{ $donation->donationID }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $donation->charity->charName ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $donation->order->buyer->fullName ?? 'Anonymous' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <a href="{{ route('admin.orders.show', $donation->orderID) }}" class="text-forest hover:text-forest-dark">#{{ $donation->orderID }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-pink-600">₱{{ number_format($donation->amountDonated, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $donation->dateDonated ? $donation->dateDonated->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No donations found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($donations->hasPages())<div class="px-6 py-4 border-t border-gray-100">{{ $donations->links() }}</div>@endif
    </div>
</div>
@endsection
