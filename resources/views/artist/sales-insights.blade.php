@extends('artist.layouts.app')

@section('title', 'Sales Insights')
@section('page-title', 'AI Sales Insights')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .insight-card {
        transition: all 0.3s ease;
    }
    .insight-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .ai-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .forecast-line {
        border-left: 3px dashed #C4A35A;
    }
    .pulse-dot {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- AI Header --}}
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl p-6 text-white">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold">Predictive Sales Insights</h2>
                <p class="text-white/70 text-sm">AI-powered analytics to help you grow your business</p>
            </div>
            <span class="ml-auto ai-badge px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1">
                <span class="w-2 h-2 bg-green-300 rounded-full pulse-dot"></span> AI Active
            </span>
        </div>
    </div>

    {{-- Summary Stats --}}
    @php $stats = $insights['summary_stats']; @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="insight-card bg-white rounded-xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">₱{{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
        <div class="insight-card bg-white rounded-xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Last 30 Days</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">₱{{ number_format($stats['last_30_revenue'], 2) }}</p>
            <div class="flex items-center gap-1 mt-1">
                @if($stats['growth_rate'] >= 0)
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    <span class="text-sm text-green-600 font-medium">{{ $stats['growth_rate'] }}%</span>
                @else
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    <span class="text-sm text-red-600 font-medium">{{ $stats['growth_rate'] }}%</span>
                @endif
            </div>
        </div>
        <div class="insight-card bg-white rounded-xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Avg Order Value</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">₱{{ number_format($stats['avg_order_value'], 2) }}</p>
        </div>
        <div class="insight-card bg-white rounded-xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Repeat Buyers</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['repeat_buyers'] }}</p>
        </div>
    </div>

    {{-- Revenue Forecast Chart --}}
    @php $forecast = $insights['revenue_forecast']; @endphp
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Revenue Forecast</h3>
            <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full font-medium">AI Predicted</span>
        </div>
        @if(count($forecast['historical']) >= 2)
            <canvas id="forecastChart" height="120"></canvas>
        @else
            <div class="text-center py-12 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <p>Not enough historical data for forecasting yet.</p>
                <p class="text-sm mt-1">At least 2 months of sales data is needed.</p>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Best Performing Products --}}
        @php $perf = $insights['product_performance']; @endphp
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Top Performers
            </h3>
            @if($perf['best']->count() > 0)
                <div class="space-y-3">
                    @foreach($perf['best'] as $product)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            @if($product->prodImage)
                                <img src="{{ asset($product->prodImage) }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 truncate">{{ $product->prodName }}</p>
                                <p class="text-xs text-gray-500">{{ $product->orders_count }} orders · ★ {{ $product->avg_rating }}</p>
                            </div>
                            <p class="text-sm font-semibold text-green-600">₱{{ number_format($product->total_revenue, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-6">No product data available yet.</p>
            @endif
        </div>

        {{-- Needs Attention --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Needs Attention
            </h3>
            @if($perf['worst']->count() > 0)
                <div class="space-y-3">
                    @foreach($perf['worst'] as $product)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            @if($product->prodImage)
                                <img src="{{ asset($product->prodImage) }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 truncate">{{ $product->prodName }}</p>
                                <p class="text-xs text-gray-500">{{ $product->orders_count }} orders · ★ {{ $product->avg_rating }}</p>
                            </div>
                            <p class="text-sm font-semibold text-red-500">₱{{ number_format($product->total_revenue, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-6">No product data available yet.</p>
            @endif
        </div>
    </div>

    {{-- Pricing Suggestions --}}
    @php $pricing = $insights['pricing_suggestions']; @endphp
    @if(count($pricing) > 0)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                AI Pricing Suggestions
            </h3>
            <div class="space-y-3">
                @foreach($pricing as $suggestion)
                    <div class="border border-purple-100 rounded-xl p-4 bg-purple-50/50">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-gray-800">{{ $suggestion['product']->prodName }}</h4>
                            <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">AI Suggestion</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $suggestion['reason'] }}</p>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="text-gray-500">Current: <strong class="text-gray-800">₱{{ number_format($suggestion['current_price'], 2) }}</strong></span>
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            <span class="text-purple-700 font-semibold">Suggested: ₱{{ number_format($suggestion['suggested_price'], 2) }}</span>
                            <span class="text-gray-400 ml-auto">Category Avg: ₱{{ number_format($suggestion['category_avg'], 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Peak Sales Periods --}}
    @php $peaks = $insights['peak_periods']; @endphp
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales by Day of Week</h3>
            @if($peaks['by_day']->count() > 0)
                <canvas id="dayChart" height="160"></canvas>
            @else
                <p class="text-gray-400 text-center py-6">Not enough data available.</p>
            @endif
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Sales Pattern</h3>
            @if($peaks['by_month']->count() > 0)
                <canvas id="monthlyChart" height="160"></canvas>
            @else
                <p class="text-gray-400 text-center py-6">Not enough data available.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Revenue Forecast Chart
    @if(count($forecast['historical']) >= 2)
    const historicalLabels = {!! json_encode(array_column($forecast['historical'], 'label')) !!};
    const historicalData = {!! json_encode(array_column($forecast['historical'], 'revenue')) !!};
    const forecastLabels = {!! json_encode(array_column($forecast['forecast'], 'label')) !!};
    const forecastData = {!! json_encode(array_column($forecast['forecast'], 'revenue')) !!};

    const allLabels = [...historicalLabels, ...forecastLabels];
    const actualLine = [...historicalData, ...Array(forecastLabels.length).fill(null)];
    const predictedLine = [...Array(historicalLabels.length - 1).fill(null), historicalData[historicalData.length - 1], ...forecastData];

    new Chart(document.getElementById('forecastChart'), {
        type: 'line',
        data: {
            labels: allLabels,
            datasets: [
                {
                    label: 'Actual Revenue',
                    data: actualLine,
                    borderColor: '#C4A35A',
                    backgroundColor: 'rgba(196,163,90,0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                },
                {
                    label: 'AI Forecast',
                    data: predictedLine,
                    borderColor: '#8b5cf6',
                    borderDash: [6, 4],
                    backgroundColor: 'rgba(139,92,246,0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointStyle: 'triangle',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => '₱' + v.toLocaleString() } } }
        }
    });
    @endif

    // Day of Week Chart
    @if($peaks['by_day']->count() > 0)
    new Chart(document.getElementById('dayChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($peaks['by_day']->pluck('day')) !!},
            datasets: [{
                label: 'Orders',
                data: {!! json_encode($peaks['by_day']->pluck('orders')) !!},
                backgroundColor: 'rgba(196,163,90,0.7)',
                borderRadius: 6,
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
    @endif

    // Monthly Pattern Chart
    @if($peaks['by_month']->count() > 0)
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($peaks['by_month']->pluck('month')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($peaks['by_month']->pluck('revenue')) !!},
                backgroundColor: 'rgba(139,92,246,0.7)',
                borderRadius: 6,
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: v => '₱' + v.toLocaleString() } } } }
    });
    @endif
</script>
@endpush
@endsection
