@extends('admin.layouts.app')
@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="prodName" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                <input type="text" id="prodName" name="prodName" value="{{ old('prodName') }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none @error('prodName') border-red-500 @enderror" required>
                @error('prodName')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="artistID" class="block text-sm font-medium text-gray-700 mb-2">Artist *</label>
                    <select id="artistID" name="artistID" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none @error('artistID') border-red-500 @enderror" required>
                        <option value="">Select Artist</option>
                        @foreach($artists as $artist)
                            <option value="{{ $artist->artistID }}" {{ old('artistID') == $artist->artistID ? 'selected' : '' }}>{{ $artist->fullName }}</option>
                        @endforeach
                    </select>
                    @error('artistID')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="categoryID" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select id="categoryID" name="categoryID" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none @error('categoryID') border-red-500 @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->categoryID }}" {{ old('categoryID') == $category->categoryID ? 'selected' : '' }}>{{ $category->catName }}</option>
                        @endforeach
                    </select>
                    @error('categoryID')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label for="prodDesc" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="prodDesc" name="prodDesc" rows="4" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">{{ old('prodDesc') }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="prodPrice" class="block text-sm font-medium text-gray-700 mb-2">Price (₱) *</label>
                    <input type="number" id="prodPrice" name="prodPrice" value="{{ old('prodPrice') }}" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none @error('prodPrice') border-red-500 @enderror" required>
                    @error('prodPrice')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="prodStatus" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="prodStatus" name="prodStatus" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" required>
                        <option value="available" {{ old('prodStatus') === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="unavailable" {{ old('prodStatus') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="prodImage" class="block text-sm font-medium text-gray-700 mb-2">Image URL</label>
                <input type="text" id="prodImage" name="prodImage" value="{{ old('prodImage') }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" placeholder="https://example.com/image.jpg">
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-forest hover:bg-forest-dark text-white px-6 py-2 rounded-lg transition-colors">Create Product</button>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
