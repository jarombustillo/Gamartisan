@extends('admin.layouts.app')

@section('title', 'Report Generation')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Report Generation</h1>
    <p class="text-gray-500 mt-1">Generate and view reports for your platform</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Sales Report -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Sales Report</h3>
        <p class="text-sm text-gray-500 mb-4">View order totals, revenue breakdown, platform fees, and seller earnings.</p>
        <form action="{{ route('admin.reports.sales') }}" method="GET">
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div>
                    <label class="text-xs text-gray-500">From</label>
                    <input type="date" name="date_from" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none">
                </div>
                <div>
                    <label class="text-xs text-gray-500">To</label>
                    <input type="date" name="date_to" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none">
                </div>
            </div>
            <button type="submit" class="w-full bg-forest text-white py-2.5 rounded-lg font-medium hover:bg-forest-dark transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Generate Sales Report
            </button>
        </form>
    </div>

    <!-- Donation Report -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Donation Report</h3>
        <p class="text-sm text-gray-500 mb-4">Track charitable donations grouped by charity with total amounts.</p>
        <form action="{{ route('admin.reports.donations') }}" method="GET">
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div>
                    <label class="text-xs text-gray-500">From</label>
                    <input type="date" name="date_from" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none">
                </div>
                <div>
                    <label class="text-xs text-gray-500">To</label>
                    <input type="date" name="date_to" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none">
                </div>
            </div>
            <button type="submit" class="w-full bg-pink-600 text-white py-2.5 rounded-lg font-medium hover:bg-pink-700 transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Generate Donation Report
            </button>
        </form>
    </div>

    <!-- Artist Performance -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-ochre/20 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-ochre" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Artist Performance</h3>
        <p class="text-sm text-gray-500 mb-4">Analyze artist sales, orders, earnings, and product counts.</p>
        <form action="{{ route('admin.reports.artist-performance') }}" method="GET">
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div>
                    <label class="text-xs text-gray-500">From</label>
                    <input type="date" name="date_from" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none">
                </div>
                <div>
                    <label class="text-xs text-gray-500">To</label>
                    <input type="date" name="date_to" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none">
                </div>
            </div>
            <button type="submit" class="w-full bg-ochre text-white py-2.5 rounded-lg font-medium hover:bg-ochre/90 transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Generate Artist Report
            </button>
        </form>
    </div>
</div>
@endsection
