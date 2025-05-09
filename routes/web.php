<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\KategoriController;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// =============================
// Autentikasi
// =============================
Auth::routes(); // Laravel UI auth

// =============================
// Dashboard berdasarkan role
// =============================
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');

Route::middleware(['auth', 'user-access:manager'])->group(function () {
    Route::get('/manager/home', [HomeController::class, 'managerHome'])->name('manager.home');
});

// Logout
Route::post('/logout', [LogoutController::class, 'signout'])->name('logout');

// =============================
// Route hanya untuk pengguna yang login
// =============================
Route::middleware(['auth'])->group(function () {

    // Produk
    Route::resource('produk', ProdukController::class);

    // Keranjang
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/store', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::post('/keranjang/update', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::post('/keranjang/remove', [KeranjangController::class, 'remove'])->name('keranjang.remove');
    Route::post('/keranjang/clear', [KeranjangController::class, 'clear'])->name('keranjang.clear');
    Route::get('/keranjang/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');

    // Transaksi
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/history', [TransactionsController::class, 'history'])->name('transactions.history');
    Route::get('/transactions/print/{id}', [TransactionsController::class, 'print'])->name('transactions.print');
    Route::get('/transactions/{id}/receipt', [TransactionsController::class, 'printReceipt'])->name('transactions.receipt');
    Route::put('/transactions/{id}/updatestatus', [TransactionsController::class, 'updateStatus'])->name('transactions.updateStatus');

    // Kategori (CRUD)
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

   // Gunakan hanya method yang kamu perlukan
Route::get('/produk/live-search', [ProdukController::class, 'liveSearch'])->name('produk.live-search');

});
