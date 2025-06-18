<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Home route
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserController::class, 'registration']);
    Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [UserController::class, 'sendOtp']);
    Route::post('/verify-otp', [UserController::class, 'verifyOtp'])->name('verify.otp');
    Route::get('/reset-password', [UserController::class, 'showResetPasswordForm'])->name('reset-password');
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
});

// Protected Routes
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    
    // Product API routes
    Route::get('/productList', [ProductController::class, 'productList']);
    Route::get('/productShow', [ProductController::class, 'productShow']);
    Route::post('/productCreate', [ProductController::class, 'productCreate']);
    Route::post('/productUpdate', [ProductController::class, 'productUpdate']);
    Route::delete('/productDelete', [ProductController::class, 'productDelete']);
    
    // Sale API routes
    Route::get('/saleList', [SaleController::class, 'saleList']);
    Route::get('/saleShow', [SaleController::class, 'saleShow']);
    Route::post('/saleCreate', [SaleController::class, 'saleCreate']);
    Route::post('/saleUpdate', [SaleController::class, 'saleUpdate']);
    Route::delete('/saleDelete', [SaleController::class, 'saleDelete']);
    Route::get('/saleSummary', [SaleController::class, 'saleSummary']);
    
    // Invoice routes
    Route::get('/downloadInvoice', [InvoiceController::class, 'downloadInvoice']);
    Route::get('/previewInvoice', [InvoiceController::class, 'previewInvoice']);
    
    // User routes
    Route::get('/userProfile', [UserController::class, 'userProfile']);
    Route::post('/userUpdate', [UserController::class, 'userUpdate']);
});

Route::middleware([\App\Http\Middleware\TokenVerify::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    
    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    
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
    Route::get('/salesList', [SaleController::class, 'salesList']);
    Route::get('/saleShow', [SaleController::class, 'saleShow']);
    Route::post('/saleCreate', [SaleController::class, 'saleCreate']);
    Route::post('/saleUpdate', [SaleController::class, 'saleUpdate']);
    Route::delete('/saleDelete', [SaleController::class, 'saleDelete']);
    
    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
});


