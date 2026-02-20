@extends('admin.layouts.app')
@section('title', 'Edit Charity')
@section('page-title', 'Edit Charity')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.charities.update', $charity) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            <div>
                <label for="charName" class="block text-sm font-medium text-gray-700 mb-2">Charity Name *</label>
                <input type="text" id="charName" name="charName" value="{{ old('charName', $charity->charName) }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" required>
            </div>
            <div>
                <label for="charDesc" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="charDesc" name="charDesc" rows="4" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">{{ old('charDesc', $charity->charDesc) }}</textarea>
            </div>
            <div>
                <label for="charConDetails" class="block text-sm font-medium text-gray-700 mb-2">Contact Details</label>
                <input type="text" id="charConDetails" name="charConDetails" value="{{ old('charConDetails', $charity->charConDetails) }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
            </div>
            <div>
                <label for="charStatus" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select id="charStatus" name="charStatus" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" required>
                    <option value="available" {{ old('charStatus', $charity->charStatus) === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ old('charStatus', $charity->charStatus) === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-forest hover:bg-forest-dark text-white px-6 py-2 rounded-lg transition-colors">Update Charity</button>
                <a href="{{ route('admin.charities.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
