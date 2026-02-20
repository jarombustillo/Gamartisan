@extends('admin.layouts.app')

@section('title', 'Add Buyer')
@section('page-title', 'Add New Buyer')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.buyers.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input 
                        type="text" 
                        id="firstName" 
                        name="firstName" 
                        value="{{ old('firstName') }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none @error('firstName') border-red-500 @enderror"
                        required
                    >
                    @error('firstName')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="midName" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                    <input 
                        type="text" 
                        id="midName" 
                        name="midName" 
                        value="{{ old('midName') }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none"
                    >
                </div>
            </div>

            <div>
                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                <input 
                    type="text" 
                    id="lastName" 
                    name="lastName" 
                    value="{{ old('lastName') }}"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none @error('lastName') border-red-500 @enderror"
                    required
                >
                @error('lastName')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none @error('email') border-red-500 @enderror"
                    required
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none @error('password') border-red-500 @enderror"
                        required
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none"
                        required
                    >
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-forest hover:bg-forest-dark text-white px-6 py-2 rounded-lg transition-colors">
                    Create Buyer
                </button>
                <a href="{{ route('admin.buyers.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
