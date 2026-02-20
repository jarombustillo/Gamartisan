@extends('artist.layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('artist.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="prodName" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                <input type="text" id="prodName" name="prodName" value="{{ old('prodName') }}"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="categoryID" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select id="categoryID" name="categoryID" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->categoryID }}" {{ old('categoryID') == $category->categoryID ? 'selected' : '' }}>{{ $category->catName }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="prodPrice" class="block text-sm font-medium text-gray-700 mb-2">Price (₱) *</label>
                    <input type="number" id="prodPrice" name="prodPrice" value="{{ old('prodPrice') }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none" required>
                </div>
            </div>

            <div>
                <label for="prodDesc" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="prodDesc" name="prodDesc" rows="4"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none">{{ old('prodDesc') }}</textarea>
            </div>

            <div>
                <label for="prodImage" class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                <input type="file" id="prodImage" name="prodImage" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-ochre file:text-white hover:file:bg-ochre/90"
                    onchange="previewImage(this)">
                <p class="text-xs text-gray-500 mt-1">Accepted formats: JPEG, PNG, JPG, GIF, WebP. Max size: 2MB</p>
                <div id="imagePreview" class="mt-3 hidden">
                    <img id="previewImg" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                </div>
            </div>

            <div>
                <label for="prodStatus" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select id="prodStatus" name="prodStatus" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none" required>
                    <option value="available" {{ old('prodStatus') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ old('prodStatus') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-ochre hover:bg-ochre/90 text-white px-6 py-2 rounded-lg transition-colors">
                    Create Product
                </button>
                <a href="{{ route('artist.products') }}" class="px-6 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endsection
