@extends('admin.layouts.app')
@section('title', 'Add Charity')
@section('page-title', 'Add New Charity')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.charities.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="charName" class="block text-sm font-medium text-gray-700 mb-2">Charity Name *</label>
                <input type="text" id="charName" name="charName" value="{{ old('charName') }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none @error('charName') border-red-500 @enderror" required>
                @error('charName')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="charDesc" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="charDesc" name="charDesc" rows="4" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">{{ old('charDesc') }}</textarea>
            </div>
            <div>
                <label for="charConDetails" class="block text-sm font-medium text-gray-700 mb-2">Contact Details</label>
                <input type="text" id="charConDetails" name="charConDetails" value="{{ old('charConDetails') }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
            </div>
            <div>
                <label for="charStatus" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select id="charStatus" name="charStatus" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" required>
                    <option value="available" {{ old('charStatus') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ old('charStatus') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-forest hover:bg-forest-dark text-white px-6 py-2 rounded-lg transition-colors">Create Charity</button>
                <a href="{{ route('admin.charities.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
