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
    </style>
    
    @stack('styles')
</head>

<body class="bg-cream">
    @yield('content')
    
    @stack('scripts')
</body>

</html>
