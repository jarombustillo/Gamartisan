@extends('layouts.app')

@section('title', 'Artist Registration')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-6">
        <!-- Back Button -->
        <a href="{{ route('join') }}" class="inline-flex items-center text-gray-800 hover:text-forest transition-colors mb-6">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>

        <!-- Registration Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="flex items-center justify-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-ochre/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-ochre" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Artist Registration</h1>
                    <p class="text-gray-500 text-sm">Start selling your handmade creations</p>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.seller.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" id="firstName" name="firstName" value="{{ old('firstName') }}" required
                            class="w-full px-4 py-3 bg-white rounded-lg border border-gray-300 focus:border-forest focus:ring-2 focus:ring-forest/20 outline-none transition-all"
                            placeholder="First name">
                    </div>
                    <div>
                        <label for="midName" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                        <input type="text" id="midName" name="midName" value="{{ old('midName') }}"
                            class="w-full px-4 py-3 bg-white rounded-lg border border-gray-300 focus:border-forest focus:ring-2 focus:ring-forest/20 outline-none transition-all"
                            placeholder="Middle name">
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" id="lastName" name="lastName" value="{{ old('lastName') }}" required
                            class="w-full px-4 py-3 bg-white rounded-lg border border-gray-300 focus:border-forest focus:ring-2 focus:ring-forest/20 outline-none transition-all"
                            placeholder="Last name">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-white rounded-lg border border-gray-300 focus:border-forest focus:ring-2 focus:ring-forest/20 outline-none transition-all"
                        placeholder="Enter your email">
                </div>

                <div>
                    <label for="artBio" class="block text-sm font-medium text-gray-700 mb-2">About You</label>
                    <textarea id="artBio" name="artBio" rows="3"
                        class="w-full px-4 py-3 bg-white rounded-lg border border-gray-300 focus:border-forest focus:ring-2 focus:ring-forest/20 outline-none transition-all"
                        placeholder="Tell us about yourself and your craft...">{{ old('artBio') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 bg-white rounded-lg border border-gray-300 focus:border-forest focus:ring-2 focus:ring-forest/20 outline-none transition-all"
                            placeholder="Create a password">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-3 bg-white rounded-lg border border-gray-300 focus:border-forest focus:ring-2 focus:ring-forest/20 outline-none transition-all"
                            placeholder="Confirm your password">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="terms" name="terms" required
                        class="w-4 h-4 text-forest border-gray-300 rounded focus:ring-forest">
                    <label for="terms" class="ml-3 text-sm text-gray-700">
                        I agree to the Terms of Service and Seller Agreement
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-forest text-white font-semibold py-4 rounded-lg hover:bg-forest-dark transition-all shadow-md hover:shadow-lg">
                    Create Artist Account
                </button>
            </form>

            <div class="text-center mt-6">
                <p class="text-gray-600">Already have an account?</p>
                <a href="{{ route('login') }}" class="text-forest font-semibold hover:text-forest-dark transition-colors">
                    Sign in
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
