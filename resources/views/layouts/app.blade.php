<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gamartisan - Crafted to Give. Made to Matter.')</title>
    <meta name="description" content="Gamartisan is a haven for handmade stories. Every purchase supports artisans and gives back to charity.">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
                        'terracotta': '#C67B5C',
                        'sage': '#8FAE8B',
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

        .hero-gradient {
            background: linear-gradient(135deg, #FAF8F5 0%, #F5F1EA 100%);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .product-card {
            transition: all 0.3s ease;
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: #2D5A3D;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-primary {
            background: #2D5A3D;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #1E3D2A;
            transform: scale(1.05);
        }

        .quote-section {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5));
        }

        .cta-section {
            background: linear-gradient(135deg, #2D5A3D 0%, #1E3D2A 100%);
        }

        .sidebar-link {
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            color: #2D5A3D;
            transform: translateX(4px);
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .float-animation {
            animation: float 4s ease-in-out infinite;
        }
    </style>
    
    @stack('styles')
</head>

<body class="bg-cream">
    @include('partials.header')
    
    @yield('content')
    
    @include('partials.footer')
    
    @stack('scripts')
</body>

</html>
