@extends('buyer.layouts.app')

@section('title', 'Transaction Logs')
@section('page-title', 'Transaction Logs')

@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('buyer.transactions') }}" method="GET" class="flex gap-4">
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                <option value="">All Payment Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors">Filter</button>
            @if(request('status'))
                <a href="{{ route('buyer.transactions') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Method</th>
                        <th class="px-6 py-4">Pay Status</th>
                        <th class="px-6 py-4">Donation</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">#{{ $order->orderID }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->product->prodName ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">₱{{ number_format($order->ordTotalPrice, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->payment->payMethod ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @if($order->payment)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $order->payment->payStatus === 'completed' ? 'bg-green-100 text-green-700' : ($order->payment->payStatus === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($order->payment->payStatus) }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($order->donation)
                                    <span class="text-pink-600 font-medium">₱{{ number_format($order->donation->amountDonated, 2) }}</span>
                                    <span class="text-gray-400 text-xs block">→ {{ $order->donation->charity->charName ?? 'N/A' }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->orderDate ? $order->orderDate->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <p>No transaction records yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
@endsection
