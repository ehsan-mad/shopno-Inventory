{{-- filepath: c:\dev\laravel\shopno-Inventory\resources\views\components\sidebar.blade.php --}}
<aside class="hidden lg:flex lg:flex-shrink-0">
    <div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
        <div class="flex items-center space-x-2 px-4">
            <i class="fas fa-store text-2xl"></i>
            <span class="text-2xl font-extrabold">ShopNo</span>
        </div>

        <!-- Nav -->
        <nav>            <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
            <a href="{{ route('products.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('products*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                <i class="fas fa-box mr-2"></i>Products
            </a>
            <a href="{{ route('categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('categories*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                <i class="fas fa-tags mr-2"></i>Categories
            </a>
            <a href="{{ route('customers.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('customers*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                <i class="fas fa-users mr-2"></i>Customers
            </a>
            <a href="{{ route('sales.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('sales*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                <i class="fas fa-shopping-cart mr-2"></i>Sales
            </a>
            <a href="{{ route('invoices.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('invoices*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                <i class="fas fa-file-invoice mr-2"></i>Invoices
            </a>
        </nav>
    </div>
</aside>
