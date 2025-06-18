<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            // Debug output
            dd('User is null in DashboardController', auth()->check(), auth()->user(), $request->headers->get('user_id'));
        }

        $activeCustomers = Customer::where('status', true)->count();
        $totalSales = Sale::where('user_id', $user->id)->count();
        $cancelledSales = Sale::where('user_id', $user->id)->where('status', 'cancelled')->count();
        $pendingSales = Sale::where('user_id', $user->id)->where('status', 'pending')->count();
        $productStock = Product::where('user_id', $user->id)->sum('stock_quantity');
        $totalRevenue = Sale::where('user_id', $user->id)->sum('total');
        $averageSaleValue = Sale::where('user_id', $user->id)->avg('total');

        return view('dashboard', [
            'user' => $user,
            'activeCustomers' => $activeCustomers,
            'totalSales' => $totalSales,
            'cancelledSales' => $cancelledSales,
            'pendingSales' => $pendingSales,
            'productStock' => $productStock,
            'totalRevenue' => $totalRevenue,
            'averageSaleValue' => $averageSaleValue
        ]);
    }
} 