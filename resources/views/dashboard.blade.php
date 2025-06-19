@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Title -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 mb-1">Welcome back, {{ $user->first_name }}!</h1>
            <p class="text-base text-gray-500">Here's what's happening with your inventory today.</p>
        </div>
        
    </div>

    <!-- Summary Section -->
    <div class="summary-section mt-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-chart-pie mr-3 text-indigo-500"></i> Dashboard Summary
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <i class="fas fa-users text-indigo-400 text-3xl mb-2"></i>
                <h3 class="text-lg font-semibold text-gray-700">Active Customers</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $activeCustomers }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <i class="fas fa-shopping-cart text-green-400 text-3xl mb-2"></i>
                <h3 class="text-lg font-semibold text-gray-700">Total Sales</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalSales }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <i class="fas fa-times-circle text-red-400 text-3xl mb-2"></i>
                <h3 class="text-lg font-semibold text-gray-700">Cancelled Sales</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $cancelledSales }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <i class="fas fa-clock text-yellow-400 text-3xl mb-2"></i>
                <h3 class="text-lg font-semibold text-gray-700">Pending Sales</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $pendingSales }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <i class="fas fa-cubes text-blue-400 text-3xl mb-2"></i>
                <h3 class="text-lg font-semibold text-gray-700">Product Stock</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $productStock }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <i class="fas fa-coins text-green-400 text-3xl mb-2"></i>
                <h3 class="text-lg font-semibold text-gray-700">Total Revenue</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalRevenue }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <i class="fas fa-chart-line text-indigo-400 text-3xl mb-2"></i>
                <h3 class="text-lg font-semibold text-gray-700">Average Sale Value</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $averageSaleValue }}</p>
            </div>
        </div>
    </div>
@endsection 