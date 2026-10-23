@extends('admin.layouts.app')

@section('title', 'Behavioral Analytics')
@section('page-title', 'Behavioral Analytics')

@push('styles')
<style>
    .metric-card {
        transition: all 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .funnel-bar {
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .segment-dot {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- AI Header --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl p-6 text-white">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold">Behavioral Analytics for User Engagement</h2>
                <p class="text-white/70 text-sm">Understand your users through AI-powered behavioral analysis</p>
            </div>
            <span class="ml-auto bg-white/20 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1">
                <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span> AI Active
            </span>
        </div>
    </div>

    {{-- Engagement Score & Buyer Segments --}}
    @php $engagement = $analytics['engagement_metrics']; $segments = $analytics['buyer_segments']; @endphp
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Average Engagement Score --}}
        <div class="metric-card bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Avg Engagement Score</h3>
            <div class="flex items-center justify-center">
                <div class="relative w-32 h-32">
                    <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="#e5e7eb" stroke-width="10"/>
                        <circle cx="60" cy="60" r="50" fill="none" stroke="{{ $engagement['avg_score'] >= 50 ? '#10b981' : ($engagement['avg_score'] >= 25 ? '#f59e0b' : '#ef4444') }}" stroke-width="10" stroke-linecap="round" stroke-dasharray="{{ $engagement['avg_score'] * 3.14 }} 314"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-3xl font-bold text-gray-800">{{ $engagement['avg_score'] }}</span>
                    </div>
                </div>
            </div>
            <p class="text-center text-sm text-gray-500 mt-3">out of 100</p>
        </div>

        {{-- Engagement Distribution --}}
        <div class="metric-card bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Engagement Distribution</h3>
            <canvas id="engagementChart" height="180"></canvas>
        </div>

        {{-- Buyer Segments --}}
        <div class="metric-card bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Buyer Segments</h3>
            <canvas id="segmentChart" height="180"></canvas>
        </div>
    </div>

    {{-- Segment Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($segments as $key => $segment)
            @php
                $colors = [
                    'blue' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
                    'green' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-700', 'dot' => 'bg-green-500'],
                    'yellow' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-700', 'dot' => 'bg-yellow-500'],
                    'red' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-700', 'dot' => 'bg-red-500'],
                ];
                $c = $colors[$segment['color']] ?? $colors['blue'];
            @endphp
            <div class="metric-card {{ $c['bg'] }} border {{ $c['border'] }} rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-3 h-3 {{ $c['dot'] }} rounded-full segment-dot"></span>
                    <span class="text-sm font-medium {{ $c['text'] }}">{{ $segment['label'] }}</span>
                </div>
                <p class="text-3xl font-bold {{ $c['text'] }}">{{ $segment['count'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Conversion Funnel --}}
    @php $funnel = $analytics['conversion_funnel']; @endphp
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            Conversion Funnel
        </h3>
        <div class="space-y-4">
            @php $funnelColors = ['#2D5A3D', '#3D7A50', '#C4A35A', '#C67B5C', '#8FAE8B']; @endphp
            @foreach($funnel as $i => $stage)
                <div class="flex items-center gap-4">
                    <div class="w-36 text-sm font-medium text-gray-700 text-right">{{ $stage['stage'] }}</div>
                    <div class="flex-1 bg-gray-100 rounded-full h-10 relative overflow-hidden">
                        <div class="funnel-bar h-full rounded-full flex items-center px-4" style="width: {{ max($stage['rate'], 3) }}%; background-color: {{ $funnelColors[$i] ?? '#6b7280' }};">
                            <span class="text-white text-sm font-semibold whitespace-nowrap">{{ $stage['count'] }} ({{ $stage['rate'] }}%)</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Cohort Retention --}}
    @php $cohorts = $analytics['cohort_retention']; @endphp
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Cohort Retention
            <span class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded-full ml-2">Monthly Cohorts</span>
        </h3>
        @if(count($cohorts) > 0 && collect($cohorts)->sum('size') > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-3 font-semibold text-gray-600">Cohort</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Users</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Month 1</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Month 2</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Month 3</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Month 4</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Month 5</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cohorts as $cohort)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-3 font-medium text-gray-800">{{ $cohort['label'] }}</td>
                                <td class="py-3 px-3 text-center font-semibold">{{ $cohort['size'] }}</td>
                                @for($m = 0; $m < 5; $m++)
                                    <td class="py-3 px-3 text-center">
                                        @if(isset($cohort['retention'][$m]))
                                            @php
                                                $rate = $cohort['retention'][$m]['rate'];
                                                $bg = $rate >= 50 ? 'bg-green-100 text-green-700' : ($rate >= 25 ? 'bg-yellow-100 text-yellow-700' : ($rate > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-50 text-gray-400'));
                                            @endphp
                                            <span class="inline-block px-2 py-1 rounded text-xs font-medium {{ $bg }}">{{ $rate }}%</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-400 text-center py-8">Not enough order data for cohort analysis yet.</p>
        @endif
    </div>

    {{-- Top Engaged Users --}}
    @if(count($engagement['scores']) > 0)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                Top Engaged Users
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-3 font-semibold text-gray-600">User</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Score</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Orders</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Reviews</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Last Active</th>
                            <th class="text-right py-3 px-3 font-semibold text-gray-600">Total Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($engagement['scores'], 0, 10) as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-indigo-600 text-xs font-bold">{{ strtoupper(substr($user['buyer']->firstName ?? 'U', 0, 1)) }}</span>
                                        </div>
                                        <span class="font-medium text-gray-800">{{ $user['buyer']->fullName ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <div class="inline-flex items-center gap-1">
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $user['score'] >= 70 ? 'bg-green-500' : ($user['score'] >= 40 ? 'bg-yellow-500' : 'bg-red-400') }}" style="width: {{ $user['score'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold">{{ $user['score'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-center">{{ $user['order_count'] }}</td>
                                <td class="py-3 px-3 text-center">{{ $user['review_count'] }}</td>
                                <td class="py-3 px-3 text-center text-gray-500">{{ $user['days_since_last'] !== null ? $user['days_since_last'] . 'd ago' : 'Never' }}</td>
                                <td class="py-3 px-3 text-right font-medium">₱{{ number_format($user['total_spent'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Engagement Distribution Doughnut
    const engDist = @json($engagement['distribution']);
    new Chart(document.getElementById('engagementChart'), {
        type: 'doughnut',
        data: {
            labels: ['Highly Engaged', 'Moderate', 'Low', 'Inactive'],
            datasets: [{
                data: [engDist.highly_engaged, engDist.moderately_engaged, engDist.low_engagement, engDist.inactive],
                backgroundColor: ['#10b981', '#f59e0b', '#f97316', '#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '60%',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
        }
    });

    // Buyer Segments Pie
    const segs = @json($segments);
    const segLabels = Object.values(segs).map(s => s.label);
    const segData = Object.values(segs).map(s => s.count);
    const segColors = { blue: '#3b82f6', green: '#10b981', yellow: '#f59e0b', red: '#ef4444' };
    const segBg = Object.values(segs).map(s => segColors[s.color] || '#6b7280');

    new Chart(document.getElementById('segmentChart'), {
        type: 'doughnut',
        data: {
            labels: segLabels,
            datasets: [{
                data: segData,
                backgroundColor: segBg,
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '60%',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
        }
    });
</script>
@endpush
@endsection
