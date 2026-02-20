@extends('admin.layouts.app')
@section('title', 'Add Category')
@section('page-title', 'Add New Category')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="catName" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                <input type="text" id="catName" name="catName" value="{{ old('catName') }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none @error('catName') border-red-500 @enderror" required>
                @error('catName')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-forest hover:bg-forest-dark text-white px-6 py-2 rounded-lg transition-colors">Create Category</button>
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
