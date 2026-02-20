@extends('admin.layouts.app')

@section('title', 'Charities')
@section('page-title', 'Charities Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-gray-600">Manage charitable organizations</p>
        <a href="{{ route('admin.charities.create') }}" class="inline-flex items-center gap-2 bg-forest hover:bg-forest-dark text-white px-4 py-2 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Charity
        </a>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="{{ route('admin.charities.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search charities..." class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                <option value="">All Status</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.charities.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($charities as $charity)
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $charity->charStatus === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($charity->charStatus) }}
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $charity->charName }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ Str::limit($charity->charDesc, 80) }}</p>
                <div class="flex items-center justify-between text-sm">
                    <div>
                        <p class="text-gray-500">Total Donations</p>
                        <p class="font-semibold text-pink-600">₱{{ number_format($charity->donations_sum_amount_donated ?? 0, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500">Count</p>
                        <p class="font-semibold text-gray-800">{{ $charity->donations_count ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.charities.show', $charity) }}" class="flex-1 text-center py-2 text-forest hover:text-forest-dark text-sm font-medium">View</a>
                    <a href="{{ route('admin.charities.edit', $charity) }}" class="flex-1 text-center py-2 text-ochre hover:text-ochre/80 text-sm font-medium">Edit</a>
                    <form action="{{ route('admin.charities.destroy', $charity) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this charity?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full text-center py-2 text-red-600 hover:text-red-700 text-sm font-medium">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                <p class="text-gray-500">No charities found</p>
            </div>
        @endforelse
    </div>

    @if($charities->hasPages())<div class="mt-6">{{ $charities->links() }}</div>@endif
</div>
@endsection
