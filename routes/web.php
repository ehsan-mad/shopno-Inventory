<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Pages (GET routes)
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
    Route::get('/verify-otp', function () {
        return view('auth.verify-otp');
    })->name('verify.otp');
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    Route::get('/reset-password', function () {
        return view('auth.reset-password');
    })->name('reset.password');
});

// Authentication API Routes (POST routes)
Route::post('/userLogin', [UserController::class, 'login'])->name('user.login');
Route::post('/userRegistration', [UserController::class, 'registration'])->name('user.register');
Route::post('/verifyOTP', [UserController::class, 'verifyOtp'])->name('user.verify.otp');
Route::post('/sendOTP', [UserController::class, 'sendOtp'])->name('user.send.otp');
Route::post('/resetPassword', [UserController::class, 'resetPassword'])->name('user.reset.password');
Route::post('/userLogout', [UserController::class, 'logout'])->name('user.logout');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Protected Routes with TokenVerify middleware
Route::middleware([\App\Http\Middleware\TokenVerify::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/productList', [ProductController::class, 'productList']);
    Route::get('/productShow', [ProductController::class, 'productShow']);
    Route::post('/productCreate', [ProductController::class, 'productCreate']);
    Route::post('/productUpdate', [ProductController::class, 'productUpdate']);
    Route::delete('/productDelete', [ProductController::class, 'productDelete']);
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categoryList', [CategoryController::class, 'categoryList']);
    Route::get('/categoryShow', [CategoryController::class, 'categoryShow']);
    Route::post('/categoryCreate', [CategoryController::class, 'categoryCreate']);
    Route::post('/categoryUpdate', [CategoryController::class, 'categoryUpdate']);
    Route::delete('/categoryDelete', [CategoryController::class, 'categoryDelete']);
    
    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customerList', [CustomerController::class, 'customerList']);
    Route::get('/customerShow', [CustomerController::class, 'customerShow']);
    Route::post('/customerCreate', [CustomerController::class, 'customerCreate']);
    Route::post('/customerUpdate', [CustomerController::class, 'customerUpdate']);
    Route::delete('/customerDelete', [CustomerController::class, 'customerDelete']);
    
    // Sales
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/saleList', [SaleController::class, 'saleList']);
    Route::get('/salesList', [SaleController::class, 'salesList']);
    Route::get('/saleShow', [SaleController::class, 'saleShow']);
    Route::post('/saleCreate', [SaleController::class, 'saleCreate']);
    Route::post('/saleUpdate', [SaleController::class, 'saleUpdate']);
    Route::delete('/saleDelete', [SaleController::class, 'saleDelete']);
    Route::get('/saleSummary', [SaleController::class, 'saleSummary']);
    
    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/getInvoices', [InvoiceController::class, 'getInvoices']);
    Route::get('/downloadInvoice', [InvoiceController::class, 'downloadInvoice'])->name('invoices.download');
    Route::get('/previewInvoice', [InvoiceController::class, 'previewInvoice'])->name('invoices.preview');
    
    // User Profile
    Route::get('/userProfile', [UserController::class, 'userProfile']);
    Route::post('/userUpdate', [UserController::class, 'userUpdate']);
});

// Add this route outside the middleware group for API access
Route::get('/api/latest-invoice', function (\Illuminate\Http\Request $request) {
    $customerId = $request->query('customer_id');
    if (!$customerId) {
        return response()->json(['status' => 'error', 'message' => 'customer_id is required'], 400);
    }
    $invoice = \App\Models\Invoice::where('customer_id', $customerId)
        ->orderByDesc('created_at')
        ->first();
    if ($invoice) {
        return response()->json(['status' => 'success', 'invoice' => $invoice]);
    } else {
        return response()->json(['status' => 'error', 'message' => 'No invoice found for this customer.']);
    }
});