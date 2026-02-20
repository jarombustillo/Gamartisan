@extends('admin.layouts.app')
@section('title', 'Order Details')
@section('page-title', 'Order #' . $order->orderID)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Order Details</h3>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'processing' => 'bg-blue-100 text-blue-700',
                            'shipped' => 'bg-indigo-100 text-indigo-700',
                            'delivered' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$order->ordStatus] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($order->ordStatus) }}
                    </span>
                </div>

                <div class="border border-gray-100 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-4">
                        @if($order->product && $order->product->prodImage)
                            <img src="{{ asset($order->product->prodImage) }}" alt="{{ $order->product->prodName }}" class="w-20 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">{{ $order->product->prodName ?? 'N/A' }}</h4>
                            <p class="text-sm text-gray-500">by {{ $order->artist->fullName ?? 'Unknown' }}</p>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="text-sm text-gray-600">Qty: {{ $order->ordQuantity }}</span>
                                <span class="text-sm font-medium text-forest">₱{{ number_format($order->product->prodPrice ?? 0, 2) }} each</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-gray-800">₱{{ number_format($order->ordTotalPrice, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Order Date</p>
                        <p class="font-medium text-gray-800">{{ $order->orderDate ? $order->orderDate->format('F d, Y h:i A') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Order ID</p>
                        <p class="font-medium text-gray-800">#{{ $order->orderID }}</p>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Status</h3>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="flex gap-4">
                    @csrf @method('PUT')
                    <select name="ordStatus" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                        <option value="pending" {{ $order->ordStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->ordStatus === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->ordStatus === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->ordStatus === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->ordStatus === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="px-6 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors">Update</button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-forest/10 rounded-full flex items-center justify-center">
                        <span class="text-forest font-medium">{{ strtoupper(substr($order->buyer->firstName ?? 'N', 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $order->buyer->fullName ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $order->buyer->email ?? 'N/A' }}</p>
                    </div>
                </div>
                @if($order->buyer)
                    <a href="{{ route('admin.buyers.show', $order->buyer) }}" class="text-sm text-forest hover:text-forest-dark">View Customer →</a>
                @endif
            </div>

            <!-- Payment -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment</h3>
                @if($order->payment)
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Method</span>
                            <span class="font-medium text-gray-800">{{ $order->payment->payMethod }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Amount</span>
                            <span class="font-medium text-gray-800">₱{{ number_format($order->payment->payAmount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $order->payment->payStatus === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($order->payment->payStatus) }}
                            </span>
                        </div>
                        @if($order->payment->datePaid)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Paid On</span>
                                <span class="text-gray-800">{{ $order->payment->datePaid->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No payment information</p>
                @endif
            </div>

            <!-- Donation -->
            @if($order->donation)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Donation</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Charity</span>
                            <span class="font-medium text-gray-800">{{ $order->donation->charity->charName ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Amount</span>
                            <span class="font-medium text-pink-600">₱{{ number_format($order->donation->amountDonated, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
