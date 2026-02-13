<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Romantic Gifts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Cormorant Garamond', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes pulse-slow {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }

        .float-animation { animation: float 6s ease-in-out infinite; }

        .gradient-text {
            background: linear-gradient(135deg, #D4A574 0%, #C39563 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="font-sans bg-gradient-to-br from-[#FAF8F5] via-[#F5E6D3] to-[#FAF8F5] min-h-screen overflow-x-hidden">

    <!-- Elementos decorativos de fondo -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-32 h-32 bg-[#FFE5E5] rounded-full opacity-30 blur-3xl" style="animation: pulse-slow 4s ease-in-out infinite;"></div>
        <div class="absolute bottom-20 right-10 w-40 h-40 bg-[#D4A574] rounded-full opacity-20 blur-3xl" style="animation: pulse-slow 5s ease-in-out infinite;"></div>
        <div class="absolute top-1/2 left-1/4 w-24 h-24 bg-[#FFC0CB] rounded-full opacity-25 blur-2xl" style="animation: pulse-slow 6s ease-in-out infinite;"></div>
    </div>

    @yield('content')

    @yield('scripts')
</body>
</html>
