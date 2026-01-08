<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Controller baru
use App\Http\Controllers\PosController;
use App\Http\Controllers\SupplierController;

// --- HALAMAN PUBLIK (BISA DIAKSES TANPA LOGIN) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- HALAMAN RAHASIA (HARUS LOGIN DULU) ---
Route::middleware('auth')->group(function () {
    // Tombol Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/settings/password', [AuthController::class, 'updatePassword'])->name('settings.password');

    // POS System (Kasir)
    Route::get('/', [PosController::class, 'index'])->name('pos.index');
    Route::post('/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/struk/{id}', [PosController::class, 'printStruk'])->name('pos.print');
    
    // Product & Category
    Route::post('/product', [PosController::class, 'store'])->name('product.store');
    Route::put('/product/{id}', [PosController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}', [PosController::class, 'destroy'])->name('product.destroy');
    Route::post('/category', [PosController::class, 'storeCategory'])->name('category.store');
    Route::put('/category/{id}', [PosController::class, 'updateCategory'])->name('category.update');
    Route::delete('/category/{id}', [PosController::class, 'destroyCategory'])->name('category.destroy');

    // Reports & Settings
    Route::get('/report', [PosController::class, 'report'])->name('pos.report');
    Route::post('/settings/update', [PosController::class, 'updateSettings'])->name('settings.update');

    // Supplier (Jika kamu sudah buat)
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    Route::post('/supplier/restock', [SupplierController::class, 'restock'])->name('supplier.restock');
});