@extends('admin.layouts.app')
@section('title', 'Donation Summary')
@section('page-title', 'Donation Summary')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 stat-card">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Donations</p>
                    <p class="text-2xl font-bold text-pink-600">₱{{ number_format($totalDonations, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 stat-card">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Transactions</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalDonationCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 stat-card">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Charities Supported</p>
                    <p class="text-2xl font-bold text-green-600">{{ $charities->where('donations_count', '>', 0)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Per-Charity Breakdown -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Donations by Charity</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Charity</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Donations</th>
                        <th class="px-6 py-4">Total Amount</th>
                        <th class="px-6 py-4">Avg per Donation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($charities as $charity)
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.charities.show', $charity) }}" class="font-medium text-gray-800 hover:text-forest">{{ $charity->charName }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $charity->charStatus === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($charity->charStatus) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $charity->donations_count }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-pink-600">₱{{ number_format($charity->donations_sum_amount_donated ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                ₱{{ $charity->donations_count > 0 ? number_format(($charity->donations_sum_amount_donated ?? 0) / $charity->donations_count, 2) : '0.00' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No charities found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Donations ({{ date('Y') }})</h3>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const data = months.map((_, i) => {{ json_encode($monthlyDonations) }}[i + 1] || 0);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Donations (₱)',
                data: data,
                backgroundColor: 'rgba(236, 72, 153, 0.2)',
                borderColor: 'rgb(236, 72, 153)',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: val => '₱' + val.toLocaleString() } }
            }
        }
    });
</script>
@endpush
@endsection
