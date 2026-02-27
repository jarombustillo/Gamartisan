@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Revenue -->
        <div class="stat-card bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">₱{{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-gray-500">From {{ $stats['total_orders'] }} orders</span>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="stat-card bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total_orders']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-yellow-600 font-medium">{{ $stats['pending_orders'] }} pending</span>
            </div>
        </div>

        <!-- Total Users -->
        <div class="stat-card bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Users</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total_buyers'] + $stats['total_artists']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <span>{{ $stats['total_buyers'] }} buyers • {{ $stats['total_artists'] }} artists</span>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Platform Earnings -->
        <div class="stat-card bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Platform Earnings (5%)</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">₱{{ number_format($stats['platform_earnings'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <span>Gamartisan commission</span>
            </div>
        </div>

        <!-- Total Donations -->
        <div class="stat-card bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Donations (10%)</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">₱{{ number_format($stats['total_donations'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <span>To {{ $stats['total_charities'] }} charities</span>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Status Chart -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Status</h3>
            <div class="relative h-64">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl p-6 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-forest hover:text-forest-dark font-medium">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="pb-3">Order ID</th>
                            <th class="pb-3">Customer</th>
                            <th class="pb-3">Product</th>
                            <th class="pb-3">Amount</th>
                            <th class="pb-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentOrders as $order)
                            <tr class="table-row">
                                <td class="py-3 text-sm font-medium text-gray-800">#{{ $order->orderID }}</td>
                                <td class="py-3 text-sm text-gray-600">{{ $order->buyer->fullName ?? 'N/A' }}</td>
                                <td class="py-3 text-sm text-gray-600">{{ Str::limit($order->product->prodName ?? 'N/A', 20) }}</td>
                                <td class="py-3 text-sm font-medium text-gray-800">₱{{ number_format($order->ordTotalPrice, 2) }}</td>
                                <td class="py-3">
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
                                <td colspan="5" class="py-8 text-center text-gray-500">No orders yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Products Summary -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Products</h3>
                <a href="{{ route('admin.products.index') }}" class="text-forest text-sm hover:text-forest-dark">Manage →</a>
            </div>
            <div class="text-3xl font-bold text-gray-800">{{ $stats['total_products'] }}</div>
            <p class="text-sm text-gray-500 mt-1">Total products in store</p>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-xl p-6 shadow-sm lg:col-span-2">
            <h3 class="font-semibold text-gray-800 mb-4">Top Selling Products</h3>
            <div class="space-y-3">
                @forelse($topProducts as $index => $product)
                    <div class="flex items-center gap-4">
                        <span class="w-6 h-6 rounded-full bg-forest/10 text-forest text-sm font-medium flex items-center justify-center">{{ $index + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $product->prodName }}</p>
                            <p class="text-xs text-gray-500">{{ $product->orders_count }} orders</p>
                        </div>
                        <span class="text-sm font-medium text-gray-800">₱{{ number_format($product->prodPrice, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">No products yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Order Status Doughnut Chart
    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const orderData = @json($ordersByStatus);
    
    new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
            datasets: [{
                data: [
                    orderData['pending'] || 0,
                    orderData['processing'] || 0,
                    orderData['shipped'] || 0,
                    orderData['delivered'] || 0,
                    orderData['cancelled'] || 0
                ],
                backgroundColor: [
                    '#FCD34D',
                    '#60A5FA',
                    '#818CF8',
                    '#34D399',
                    '#F87171'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            },
            cutout: '65%'
        }
    });
</script>
@endpush
@endsection
