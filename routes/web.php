<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerify;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/register', [UserController::class, 'registration']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';

Route::post('/sendOtp', [UserController::class, 'sendOtp'])->middleware([TokenVerify::class]);
Route::post('/verifyOtp', [UserController::class, 'verifyOtp'])->middleware([TokenVerify::class]);
Route::post('/resetPassword', [UserController::class, 'resetPassword'])->middleware([TokenVerify::class]);

// category routes
Route::post('/categoryCreate', [CategoryController::class, 'categoryCreate'])->middleware([TokenVerify::class]);

Route::get('/categoryList', [CategoryController::class, 'categoryList'])->middleware([TokenVerify::class]);
Route::post('/categoryById', [CategoryController::class, 'categoryShow'])->middleware([TokenVerify::class]);
Route::post('/categoryUpdate', [CategoryController::class, 'categoryUpdate'])->middleware([TokenVerify::class]);
Route::post('/categoryDelete', [CategoryController::class, 'categoryDelete'])->middleware([TokenVerify::class]);

//customer routes
Route::post('/customerCreate', [CustomerController::class, 'customerCreate'])->middleware([TokenVerify::class]);
Route::get('/customerList', [CustomerController::class, 'customerList'])->middleware([TokenVerify::class]);
Route::post('/customerById', [CustomerController::class, 'customerShow'])->middleware([TokenVerify::class]);
Route::post('/customerUpdate', [CustomerController::class, 'customerUpdate'])->middleware([TokenVerify::class]);
Route::post('/customerDelete', [CustomerController::class, 'customerDelete'])->middleware([TokenVerify::class]);

// product routes
Route::post('/productCreate', [ProductController::class, 'productCreate'])->middleware([TokenVerify::class]);
Route::get('/productList', [ProductController::class, 'productList'])->middleware([TokenVerify::class]);
Route::post('/productById', [ProductController::class, 'productShow'])->middleware([TokenVerify::class]);
Route::post('/productUpdate', [ProductController::class, 'productUpdate'])->middleware([TokenVerify::class]);
Route::post('/productDelete', [ProductController::class, 'productDelete'])->middleware([TokenVerify::class]);
Route::post('/productSearch', [ProductController::class, 'productOptions'])->middleware([TokenVerify::class]);

// sale routes
Route::post('/saleCreate', [SaleController::class, 'saleCreate'])->middleware([TokenVerify::class]);
Route::get('/salesList', [SaleController::class, 'salesList'])->middleware([TokenVerify::class]);
Route::post('/saleById', [SaleController::class, 'saleShow'])->middleware([TokenVerify::class]);
Route::post('/saleUpdate', [SaleController::class, 'saleUpdate'])->middleware([TokenVerify::class]);
Route::post('/saleDelete', [SaleController::class, 'saleDelete'])->middleware([TokenVerify::class]);
Route::post('/saleCancel', [SaleController::class, 'cancelSale'])->middleware([TokenVerify::class]);
Route::post('saleSummary', [SaleController::class, 'salesSummary'])->middleware([TokenVerify::class]);

Route::prefix('invoices')->group(function () {
    Route::post('/download', [InvoiceController::class, 'downloadInvoice'])->middleware([TokenVerify::class]);
    Route::post('/preview', [InvoiceController::class, 'previewInvoice'])->middleware([TokenVerify::class]);
});
