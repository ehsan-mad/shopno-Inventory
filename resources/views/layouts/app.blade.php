{{-- filepath: c:\dev\laravel\shopno-Inventory\resources\views\layouts\app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shopno Inventory')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <style>
        .btn-primary { @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200; }
        .btn-secondary { @apply bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200; }
        .input-field { @apply w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent; }
        .card { @apply bg-white rounded-lg shadow-lg p-6; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Header -->
    @include('components.header')
    
    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('components.footer')
    
    @stack('modals')
    @stack('scripts')
</body>
</html>