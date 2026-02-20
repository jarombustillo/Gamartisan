@extends('admin.layouts.app')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            <div>
                <label for="catName" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                <input type="text" id="catName" name="catName" value="{{ old('catName', $category->catName) }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" required>
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-forest hover:bg-forest-dark text-white px-6 py-2 rounded-lg transition-colors">Update Category</button>
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
