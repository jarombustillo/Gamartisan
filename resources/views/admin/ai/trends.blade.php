@extends('admin.layouts.app')

@section('title', 'Trend Forecasting')
@section('page-title', 'Trend Forecasting')

@push('styles')
<style>
    .trend-up { color: #10b981; }
    .trend-down { color: #ef4444; }
    .trend-stable { color: #6b7280; }
    .trend-card {
        transition: all 0.3s ease;
    }
    .trend-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .ai-glow {
        box-shadow: 0 0 20px rgba(139,92,246,0.15);
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- AI Header --}}
    <div class="bg-gradient-to-r from-forest to-forest-dark rounded-2xl p-6 text-white ai-glow">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold">Trend Forecasting for Handmade Products</h2>
                <p class="text-white/70 text-sm">AI-powered analysis of market trends, emerging products, and seasonal patterns</p>
            </div>
            <span class="ml-auto bg-white/20 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1">
                <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span> AI Active
            </span>
        </div>
    </div>

    {{-- Category Trends --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            Category Trends
            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full ml-2">Last 30 days vs previous</span>
        </h3>
        @if(count($categoryTrends) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categoryTrends as $trend)
                    <div class="trend-card border rounded-xl p-5 {{ $trend['trend'] === 'rising' ? 'border-green-200 bg-green-50/50' : ($trend['trend'] === 'declining' ? 'border-red-200 bg-red-50/50' : 'border-gray-200') }}">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-gray-800">{{ $trend['category']->catName }}</h4>
                            @if($trend['trend'] === 'rising')
                                <span class="flex items-center gap-1 text-sm font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                    Rising
                                </span>
                            @elseif($trend['trend'] === 'declining')
                                <span class="flex items-center gap-1 text-sm font-medium text-red-600 bg-red-100 px-2 py-1 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    Declining
                                </span>
                            @else
                                <span class="flex items-center gap-1 text-sm font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    Stable
                                </span>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-500">Orders</p>
                                <p class="font-bold text-gray-800">{{ $trend['current_orders'] }} <span class="text-xs {{ $trend['growth_rate'] >= 0 ? 'text-green-500' : 'text-red-500' }}">({{ $trend['growth_rate'] >= 0 ? '+' : '' }}{{ $trend['growth_rate'] }}%)</span></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Revenue</p>
                                <p class="font-bold text-gray-800">₱{{ number_format($trend['current_revenue'], 0) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Products</p>
                                <p class="font-bold text-gray-800">{{ $trend['product_count'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Rev. Growth</p>
                                <p class="font-bold {{ $trend['revenue_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $trend['revenue_growth'] >= 0 ? '+' : '' }}{{ $trend['revenue_growth'] }}%</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-400 text-center py-8">No category trend data available yet.</p>
        @endif
    </div>

    {{-- Emerging Products --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
            Emerging Products
            <span class="text-xs bg-orange-100 text-orange-600 px-2 py-1 rounded-full ml-2">Hot Picks</span>
        </h3>
        @if(count($emergingProducts) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-3 font-semibold text-gray-600">Product</th>
                            <th class="text-left py-3 px-3 font-semibold text-gray-600">Category</th>
                            <th class="text-left py-3 px-3 font-semibold text-gray-600">Artist</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Current Orders</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Previous</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Growth</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($emergingProducts as $item)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2">
                                        @if($item['product']->prodImage)
                                            <img src="{{ asset($item['product']->prodImage) }}" class="w-8 h-8 rounded-lg object-cover">
                                        @else
                                            <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                                        @endif
                                        <span class="font-medium text-gray-800">{{ $item['product']->prodName }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-gray-600">{{ $item['product']->category->catName ?? '—' }}</td>
                                <td class="py-3 px-3 text-gray-600">{{ $item['product']->artist->fullName ?? '—' }}</td>
                                <td class="py-3 px-3 text-center font-semibold">{{ $item['current_orders'] }}</td>
                                <td class="py-3 px-3 text-center text-gray-500">{{ $item['previous_orders'] }}</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-flex items-center gap-1 font-semibold {{ $item['growth_rate'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $item['growth_rate'] >= 0 ? '+' : '' }}{{ $item['growth_rate'] }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-400 text-center py-8">No emerging products detected yet.</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Seasonal Patterns --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Seasonal Patterns
            </h3>
            @if($seasonalPatterns->count() > 0)
                <canvas id="seasonalChart" height="200"></canvas>
            @else
                <p class="text-gray-400 text-center py-8">Not enough historical data yet.</p>
            @endif
        </div>

        {{-- Demand Forecast --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Demand Forecast
                <span class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded-full">AI Predicted</span>
            </h3>
            @if(count($demandForecast) > 0)
                <canvas id="demandChart" height="200"></canvas>
            @else
                <p class="text-gray-400 text-center py-8">Not enough data to forecast demand.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Seasonal Patterns Chart
    @if($seasonalPatterns->count() > 0)
    new Chart(document.getElementById('seasonalChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($seasonalPatterns->pluck('month_short')) !!},
            datasets: [
                {
                    label: 'Orders',
                    data: {!! json_encode($seasonalPatterns->pluck('orders')) !!},
                    backgroundColor: {!! json_encode($seasonalPatterns->map(function($p) {
                        return $p['season'] === 'peak' ? 'rgba(16,185,129,0.7)' : ($p['season'] === 'moderate' ? 'rgba(196,163,90,0.7)' : 'rgba(156,163,175,0.5)');
                    })) !!},
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    @endif

    // Demand Forecast Chart (stacked for multiple categories)
    @if(count($demandForecast) > 0)
    const dfColors = ['#2D5A3D', '#C4A35A', '#C67B5C', '#8FAE8B', '#667eea', '#764ba2', '#f59e0b', '#ef4444'];
    const dfDatasets = [];
    const dfLabels = new Set();

    @foreach($demandForecast as $i => $fc)
        @foreach($fc['historical'] as $h)
            dfLabels.add('{{ $h['label'] }}');
        @endforeach
        @foreach($fc['forecast'] as $f)
            dfLabels.add('{{ $f['label'] }}');
        @endforeach
    @endforeach

    const dfLabelArr = Array.from(dfLabels);

    @foreach($demandForecast as $i => $fc)
    {
        const hist = {!! json_encode($fc['historical']) !!};
        const fore = {!! json_encode($fc['forecast']) !!};
        const allData = [...hist, ...fore];
        const dataMap = {};
        allData.forEach(d => dataMap[d.label] = d.orders);
        dfDatasets.push({
            label: '{{ $fc['category']->catName }}',
            data: dfLabelArr.map(l => dataMap[l] || 0),
            backgroundColor: dfColors[{{ $i }} % dfColors.length] + '99',
            borderRadius: 4,
        });
    }
    @endforeach

    new Chart(document.getElementById('demandChart'), {
        type: 'bar',
        data: { labels: dfLabelArr, datasets: dfDatasets },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } },
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });
    @endif
</script>
@endpush
@endsection
