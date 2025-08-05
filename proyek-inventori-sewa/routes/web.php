<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda bisa mendaftarkan rute web untuk aplikasi Anda.
| Rute-rute ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditugaskan ke grup middleware "web".
|
*/

// Rute utama ('/') sekarang langsung mengarahkan ke halaman login.
// Ini memastikan halaman pertama yang dilihat pengguna adalah login.
Route::get('/', function () {
    return view('auth.login');
});

// Rute Dashboard dilindungi oleh middleware 'auth'.
// Artinya, hanya pengguna yang sudah login yang bisa mengaksesnya.
// Jika belum login, Laravel akan otomatis mengarahkan ke halaman login.
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Grup rute yang hanya bisa diakses setelah pengguna login (auth).
Route::middleware('auth')->group(function () {

    // Rute Kategori Produk
    Route::get('/baju-premium', [ProductController::class, 'index'])->defaults('category', 'Baju Premium')->name('products.premium');
    Route::get('/baju-original', [ProductController::class, 'index'])->defaults('category', 'Baju Original')->name('products.original');
    Route::get('/aksesoris', [ProductController::class, 'index'])->defaults('category', 'Aksesoris')->name('products.accessories');

    // Rute resource untuk operasi CRUD produk
    Route::resource('products', ProductController::class)->except(['index']);

    // Rute untuk menyimpan data penyewaan
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');

    // Rute untuk menampilkan halaman riwayat penyewaan
    Route::get('/rentals/history', [RentalController::class, 'history'])->name('rentals.history');

    // Rute untuk halaman profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Baris ini memuat semua rute yang diperlukan untuk autentikasi
require __DIR__.'/auth.php';
