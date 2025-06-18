<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ShopNo Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="flex items-center space-x-2 px-4">
                <i class="fas fa-store text-2xl"></i>
                <span class="text-2xl font-extrabold">ShopNo</span>
            </div>

            <!-- Nav -->
            <nav>
                <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="{{ route('products.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('products.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-box mr-2"></i>Products
                </a>
                <a href="{{ route('categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('categories.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-tags mr-2"></i>Categories
                </a>
                <a href="{{ route('customers.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('customers.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-users mr-2"></i>Customers
                </a>
                <a href="{{ route('sales.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('sales.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-shopping-cart mr-2"></i>Sales
                </a>
                <a href="{{ route('invoices.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('invoices.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-file-invoice mr-2"></i>Invoices
                </a>
            </nav>
        </div>

        <!-- Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <nav class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Mobile menu button -->
                            <button type="button" class="md:hidden px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" onclick="toggleSidebar()">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                        <div class="flex items-center">
                            <div class="ml-3 relative">
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-700">{{ $user->first_name }} {{ $user->last_name }}</span>
                                    <form action="{{ route('logout') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-gray-700 hover:text-indigo-600">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="px-4 py-6 sm:px-0">
                    <h1 class="text-2xl font-semibold text-gray-900">Welcome back, {{ $user->first_name }}!</h1>
                    <p class="mt-1 text-sm text-gray-600">Here's what's happening with your inventory today.</p>
                </div>

                <!-- Stats Section -->
                <div class="mt-8">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Total Products -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-box text-3xl text-indigo-600"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                                            <dd class="text-lg font-medium text-gray-900">0</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Items -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-3xl text-yellow-600"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Low Stock Items</dt>
                                            <dd class="text-lg font-medium text-gray-900">0</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Sales -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-dollar-sign text-3xl text-green-600"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Total Sales</dt>
                                            <dd class="text-lg font-medium text-gray-900">$0</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="mt-8">
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Activity</h3>
                        </div>
                        <div class="border-t border-gray-200">
                            <div class="px-4 py-5 sm:p-6">
                                <p class="text-gray-500">No recent activity</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="summary-section">
                    <h2>Dashboard Summary</h2>
                    <div class="summary-grid">
                        <div class="card">
                            <div class="card-body">
                                <h3>Active Customers</h3>
                                <p>{{ $activeCustomers }}</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h3>Total Sales</h3>
                                <p>{{ $totalSales }}</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h3>Cancelled Sales</h3>
                                <p>{{ $cancelledSales }}</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h3>Pending Sales</h3>
                                <p>{{ $pendingSales }}</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h3>Product Stock</h3>
                                <p>{{ $productStock }}</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h3>Total Revenue</h3>
                                <p>{{ $totalRevenue }}</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h3>Average Sale Value</h3>
                                <p>{{ $averageSaleValue }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.bg-gray-800');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
</body>
</html> 