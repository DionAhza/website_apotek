<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\FirstController;
use App\Models\Medicine;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/
Route::get('/', [FirstController::class, 'home'])->name('home');
Route::get('/about', [FirstController::class, 'about'])->name('about');

Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{medicine}', [CatalogController::class, 'show'])->name('catalog.show');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register/action', [AuthController::class, 'registerAction'])->name('register.action');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login/action', [AuthController::class, 'loginAction'])
        ->middleware('throttle:10,1')
        ->name('login.action');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Admin (wajib login + role admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('obat', MedicineController::class)
        ->parameters(['obat' => 'medicine'])
        ->names('medicines')
        ->except('show');

    Route::resource('kategori', CategoryController::class)
        ->parameters(['kategori' => 'category'])
        ->names('categories')
        ->except('show');
});

/*
|--------------------------------------------------------------------------
| Kompatibilitas URL lama
|--------------------------------------------------------------------------
*/
Route::redirect('/obat', '/admin/obat');
Route::redirect('/category', '/admin/kategori');
Route::get('/obat/{medicine}', fn (Medicine $medicine) => redirect()->route('catalog.show', $medicine))
    ->whereNumber('medicine');
