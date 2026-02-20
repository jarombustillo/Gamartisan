@extends('layouts.app')

@section('title', 'Join Gamartisan')

@section('content')
<section class="min-h-screen pt-24 pb-12 bg-cream">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Back Button -->
        <a href="{{ route('home') }}" class="inline-flex items-center text-forest-dark hover:text-forest mb-8 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>

        <!-- Header -->
        <div class="text-center mb-12">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('uploads/logo.png') }}" alt="Gamartisan Logo" class="h-24 w-auto object-contain">
            </div>
            <h1 class="font-display text-4xl lg:text-5xl font-bold text-forest-dark mb-4">
                Join Gamartisan
            </h1>
            <p class="text-lg text-gray-600">
                Choose how you'd like to get started
            </p>
        </div>

        <!-- Role Selection Cards -->
        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <!-- Buyer Card -->
            <div class="bg-white rounded-3xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-32 h-32 mb-6">
                        <svg class="w-full h-full text-forest-dark" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                    </div>
                    <h2 class="font-display text-3xl font-bold text-forest-dark mb-4">
                        I'm a Buyer
                    </h2>
                </div>

                <div class="space-y-4 mb-8">
                    <div>
                        <p class="text-gray-700">
                            <span class="font-semibold">Discover unique art</span> – Browse creations from talented artisans.
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-700">
                            <span class="font-semibold">Support good causes</span> – 10% of every purchase goes to charity.
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-700">
                            <span class="font-semibold">Fast & secure</span> – Safe payments with reliable delivery.
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-700">
                            <span class="font-semibold">Feel good shopping</span> – Support artists and give back every time.
                        </p>
                    </div>
                </div>

                <a href="{{ route('register.buyer') }}" class="block w-full bg-forest text-center text-cream font-semibold py-4 rounded-full hover:bg-forest-dark transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                    Register as Buyer
                </a>
            </div>

            <!-- Seller Card -->
            <div class="bg-white rounded-3xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-32 h-32 mb-6">
                        <svg class="w-full h-full text-forest-dark" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                            <path d="M19 2H9c-.55 0-1 .45-1 1v3h2V4h9v16H10v-2H8v3c0 .55.45 1 1 1h10c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1z" transform="translate(2, 0) scale(0.6)"/>
                        </svg>
                    </div>
                    <h2 class="font-display text-3xl font-bold text-forest-dark mb-4">
                        I'm a Seller
                    </h2>
                </div>

                <div class="space-y-4 mb-8">
                    <div>
                        <p class="text-gray-700">
                            <span class="font-semibold">Share your art</span> – Let people enjoy and support your creations.
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-700">
                            <span class="font-semibold">Make an impact</span> – A part of every sale goes to charity.
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-700">
                            <span class="font-semibold">Keep it simple</span> – Tools to manage your art with ease.
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-700">
                            <span class="font-semibold">Reach more people</span> – Connect with buyers who value handmade.
                        </p>
                    </div>
                </div>

                <a href="{{ route('register.seller') }}" class="block w-full bg-forest text-center text-cream font-semibold py-4 rounded-full hover:bg-forest-dark transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                    Register as Seller
                </a>
            </div>
        </div>

        <!-- Sign In Link -->
        <div class="text-center mt-12">
            <p class="text-gray-600">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-forest font-semibold hover:text-forest-dark transition-colors">
                    Sign in
                </a>
            </p>
        </div>
    </div>
</section>
@endsection
