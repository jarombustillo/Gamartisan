<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Gamartisan</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'forest': '#2D5A3D',
                        'forest-dark': '#1E3D2A',
                        'cream': '#FAF8F5',
                        'sand': '#F5F1EA',
                        'ochre': '#C4A35A',
                    },
                    fontFamily: {
                        'display': ['Playfair Display', 'serif'],
                        'body': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .font-display {
            font-family: 'Playfair Display', serif;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-forest via-forest-dark to-forest flex items-center justify-center p-4">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-20 w-64 h-64 bg-ochre/10 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-sage/10 rounded-full blur-3xl float-animation" style="animation-delay: 2s;"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-ochre rounded-2xl mb-4 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h1 class="font-display text-3xl text-white font-bold">Gamartisan</h1>
            <p class="text-white/60 mt-2">Admin Dashboard</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Welcome Back</h2>

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.login.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username') }}"
                            class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-forest focus:border-transparent transition-all outline-none"
                            placeholder="Enter your username"
                            required
                        >
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-forest focus:border-transparent transition-all outline-none"
                            placeholder="Enter your password"
                            required
                        >
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-forest hover:bg-forest-dark text-white font-medium py-3 rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg"
                >
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm text-forest hover:text-forest-dark transition-colors">
                    ← Back to Website
                </a>
            </div>
        </div>

        <p class="text-center text-white/40 text-sm mt-8">
            © {{ date('Y') }} Gamartisan. All rights reserved.
        </p>
    </div>
</body>

</html>
