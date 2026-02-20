@extends('admin.layouts.app')

@section('title', 'Buyers')
@section('page-title', 'Buyers Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-gray-600">Manage all registered buyers</p>
        </div>
        <a href="{{ route('admin.buyers.create') }}" class="inline-flex items-center gap-2 bg-forest hover:bg-forest-dark text-white px-4 py-2 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Buyer
        </a>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="{{ route('admin.buyers.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search by name or email..."
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none"
                >
            </div>
            <button type="submit" class="px-6 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.buyers.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Orders</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($buyers as $buyer)
                        <tr class="table-row">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $buyer->buyerID }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-forest/10 rounded-full flex items-center justify-center">
                                        <span class="text-forest font-medium text-sm">{{ strtoupper(substr($buyer->firstName, 0, 1)) }}</span>
                                    </div>
                                    <span class="text-sm text-gray-800">{{ $buyer->fullName }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $buyer->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $buyer->orders()->count() }}</td>
                            <td class="px-6 py-4">
                                @if($buyer->status === 'suspended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Suspended</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.buyers.show', $buyer) }}" class="p-2 text-gray-400 hover:text-blue-600 transition-colors" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.buyers.edit', $buyer) }}" class="p-2 text-gray-400 hover:text-ochre transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.buyers.toggle-status', $buyer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to {{ $buyer->status === 'suspended' ? 'reactivate' : 'suspend' }} this account?')">
                                        @csrf
                                        <button type="submit" class="p-2 {{ $buyer->status === 'suspended' ? 'text-green-500 hover:text-green-700' : 'text-orange-400 hover:text-orange-600' }} transition-colors" title="{{ $buyer->status === 'suspended' ? 'Reactivate' : 'Suspend' }}">
                                            @if($buyer->status === 'suspended')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.buyers.destroy', $buyer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this buyer?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p>No buyers found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($buyers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $buyers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
