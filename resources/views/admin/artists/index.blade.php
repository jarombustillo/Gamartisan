@extends('admin.layouts.app')

@section('title', 'Artists')
@section('page-title', 'Artists Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-gray-600">Manage all registered artists</p>
        <a href="{{ route('admin.artists.create') }}" class="inline-flex items-center gap-2 bg-forest hover:bg-forest-dark text-white px-4 py-2 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Artist
        </a>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="{{ route('admin.artists.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search artists..." class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
            </div>
            <button type="submit" class="px-6 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.artists.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Products</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($artists as $artist)
                        <tr class="table-row">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $artist->artistID }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-ochre/20 rounded-full flex items-center justify-center">
                                        <span class="text-ochre font-medium text-sm">{{ strtoupper(substr($artist->firstName, 0, 1)) }}</span>
                                    </div>
                                    <span class="text-sm text-gray-800">{{ $artist->fullName }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $artist->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $artist->products_count }}</td>
                            <td class="px-6 py-4">
                                @if($artist->status === 'suspended')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Suspended</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.artists.show', $artist) }}" class="p-2 text-gray-400 hover:text-blue-600" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.artists.edit', $artist) }}" class="p-2 text-gray-400 hover:text-ochre" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.artists.toggle-status', $artist) }}" method="POST" class="inline" onsubmit="return confirm('{{ $artist->status === 'suspended' ? 'Reactivate' : 'Suspend' }} this account?')">
                                        @csrf
                                        <button type="submit" class="p-2 {{ $artist->status === 'suspended' ? 'text-green-500 hover:text-green-700' : 'text-orange-400 hover:text-orange-600' }}" title="{{ $artist->status === 'suspended' ? 'Reactivate' : 'Suspend' }}">
                                            @if($artist->status === 'suspended')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                            @endif
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.artists.destroy', $artist) }}" method="POST" class="inline" onsubmit="return confirm('Delete this artist?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No artists found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($artists->hasPages())<div class="px-6 py-4 border-t border-gray-100">{{ $artists->links() }}</div>@endif
    </div>
</div>
@endsection
