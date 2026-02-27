@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="min-h-screen bg-cream flex">
    <!-- Left Side - Artistic Image -->
    <div class="hidden lg:flex lg:w-1/2 bg-cream relative flex-col">
        <div class="absolute top-8 left-8 z-10">
            <h1 class="font-display text-5xl xl:text-6xl font-black text-gray-900 leading-tight">
                Crafted to Give.<br>
                Made to Matter.
            </h1>
        </div>
        
        <div class="flex-1 flex items-center justify-center p-8">
            <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=600&h=800&fit=crop" 
                 alt="Artistic Portrait" 
                 class="max-h-[70vh] w-auto object-contain"
                 style="filter: contrast(1.1) saturate(1.2);">
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('uploads/logo.png') }}" alt="Gamartisan" class="h-16 object-contain">
                </a>
            </div>

            <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">Welcome Back</h2>

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

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email or Username</label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-gray-100 rounded-lg border-0 focus:ring-2 focus:ring-forest/20 outline-none transition-all"
                        placeholder="Enter your email or username">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 bg-gray-100 rounded-lg border-0 focus:ring-2 focus:ring-forest/20 outline-none transition-all"
                        placeholder="Enter your password">
                </div>

                <div class="flex justify-center pt-4">
                    <button type="submit"
                        class="px-12 py-3 bg-forest text-white font-semibold rounded-lg hover:bg-forest-dark transition-all shadow-md hover:shadow-lg">
                        Login
                    </button>
                </div>
            </form>

            <!-- Register Link -->
            <div class="text-center mt-8">
                <p class="text-gray-600">
                    No account yet? 
                    <a href="{{ route('join') }}" class="text-forest font-semibold hover:text-forest-dark transition-colors">
                        Register here!
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
