<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Nasabah\DashboardController as NasabahDashboard;
use App\Http\Controllers\Nasabah\ProfileController as NasabahProfile;
use App\Http\Controllers\Nasabah\GoldPriceController as NasabahGoldPrice;
use App\Http\Controllers\Nasabah\GoldSavingController as NasabahGoldSaving;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\GoldPriceController as AdminGoldPrice;
use App\Http\Controllers\Admin\TransactionController as AdminTransaction;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Articles Routes
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Events Routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// Authentication Routes (from Breeze)
require __DIR__.'/auth.php';

// Nasabah Routes
Route::middleware(['auth', 'nasabah'])->prefix('nasabah')->name('nasabah.')->group(function () {
    Route::get('/dashboard', [NasabahDashboard::class, 'index'])->name('dashboard');
    Route::get('/profile', [NasabahProfile::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [NasabahProfile::class, 'update'])->name('profile.update');
    Route::get('/gold-price', [NasabahGoldPrice::class, 'index'])->name('gold-price');
    Route::get('/gold-saving', [NasabahGoldSaving::class, 'index'])->name('gold-saving');
    Route::get('/gold-saving/buy', [NasabahGoldSaving::class, 'create'])->name('gold-saving.buy');
    Route::post('/gold-saving/buy', [NasabahGoldSaving::class, 'store'])->name('gold-saving.buy.store');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [AdminUser::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUser::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/status', [AdminUser::class, 'updateStatus'])->name('users.update-status');
    
    // Gold Prices Management
    Route::get('/gold-prices', [AdminGoldPrice::class, 'index'])->name('gold-prices.index');
    Route::get('/gold-prices/create', [AdminGoldPrice::class, 'create'])->name('gold-prices.create');
    Route::post('/gold-prices', [AdminGoldPrice::class, 'store'])->name('gold-prices.store');
    Route::get('/gold-prices/{goldPrice}/edit', [AdminGoldPrice::class, 'edit'])->name('gold-prices.edit');
    Route::patch('/gold-prices/{goldPrice}', [AdminGoldPrice::class, 'update'])->name('gold-prices.update');
    
    // Transactions Management
    Route::get('/transactions', [AdminTransaction::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [AdminTransaction::class, 'show'])->name('transactions.show');
});

// Redirect authenticated users based on role
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('nasabah.dashboard');
        }
    })->name('dashboard');
});
