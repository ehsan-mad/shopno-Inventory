{{-- filepath: c:\dev\laravel\shopno-Inventory\resources\views\layouts\auth.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Authentication - Shopno Inventory')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <style>
        .btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
        }
        
        .input-field {
            @apply w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200;
        }
        
        .card {
            @apply bg-white rounded-lg shadow-lg p-6;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            @yield('content')
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>