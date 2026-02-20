@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-gray-600">Manage product categories</p>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 bg-forest hover:bg-forest-dark text-white px-4 py-2 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Category
        </a>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
            </div>
            <button type="submit" class="px-6 py-2 bg-forest text-white rounded-lg hover:bg-forest-dark transition-colors">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-forest/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="p-2 text-gray-400 hover:text-ochre"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </form>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $category->catName }}</h3>
                <p class="text-sm text-gray-500">{{ $category->products_count }} products</p>
                <a href="{{ route('admin.categories.show', $category) }}" class="inline-block mt-4 text-sm text-forest hover:text-forest-dark">View Products →</a>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                <p class="text-gray-500">No categories found</p>
            </div>
        @endforelse
    </div>

    @if($categories->hasPages())<div class="mt-6">{{ $categories->links() }}</div>@endif
</div>
@endsection
