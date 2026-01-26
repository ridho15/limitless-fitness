<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Limitless Fitness')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-overlay {
            background: linear-gradient(135deg, rgba(0,0,0,0.95) 0%, rgba(30,30,30,0.9) 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
        }

        .primary-bg { background-color: #000000; }
        .primary-text { color: #000000; }
        .primary-border { border-color: #000000; }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #666;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #888;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-black text-white">
    <!-- Header Section -->
    <header class="gradient-overlay">
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <!-- Logo & Brand -->
            <div class="text-center">
                <div class="inline-flex items-center justify-center">
                    <img src="{{ asset('horizontal/design_4_white.png') }}" alt="Limitless Fitness" class="h-12 md:h-16">
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 py-8 mt-16">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="text-center">
                <div class="mb-4">
                    <img src="{{ asset('horizontal/design_4_white.png') }}" alt="Limitless Fitness" class="h-8 mx-auto">
                </div>
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} Limitless Fitness. All rights reserved.
                </p>
                <p class="text-gray-500 text-xs mt-2">
                    Transform your body, transform your life.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>