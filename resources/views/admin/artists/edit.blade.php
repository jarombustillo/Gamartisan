@extends('admin.layouts.app')
@section('title', 'Edit Artist')
@section('page-title', 'Edit Artist')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.artists.update', $artist) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" id="firstName" name="firstName" value="{{ old('firstName', $artist->firstName) }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" required>
                </div>
                <div>
                    <label for="midName" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                    <input type="text" id="midName" name="midName" value="{{ old('midName', $artist->midName) }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                </div>
            </div>
            <div>
                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                <input type="text" id="lastName" name="lastName" value="{{ old('lastName', $artist->lastName) }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $artist->email) }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" required>
            </div>
            <div>
                <label for="artBio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                <textarea id="artBio" name="artBio" rows="4" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">{{ old('artBio', $artist->artBio) }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="artPass" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" id="artPass" name="artPass" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none" placeholder="Leave blank to keep current">
                </div>
                <div>
                    <label for="artPass_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" id="artPass_confirmation" name="artPass_confirmation" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none">
                </div>
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-forest hover:bg-forest-dark text-white px-6 py-2 rounded-lg transition-colors">Update Artist</button>
                <a href="{{ route('admin.artists.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
