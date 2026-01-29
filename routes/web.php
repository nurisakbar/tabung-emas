<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Nasabah\DashboardController as NasabahDashboard;
use App\Http\Controllers\Nasabah\ProfileController as NasabahProfile;
use App\Http\Controllers\Nasabah\GoldPriceController as NasabahGoldPrice;
use App\Http\Controllers\Nasabah\GoldSavingController as NasabahGoldSaving;
use App\Http\Controllers\Nasabah\InstallmentPlanController as NasabahInstallmentPlan;
use App\Http\Controllers\Nasabah\PledgeController as NasabahPledge;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\GoldPriceController as AdminGoldPrice;
use App\Http\Controllers\Admin\TransactionController as AdminTransaction;
use App\Http\Controllers\Admin\InstallmentPlanController as AdminInstallmentPlan;
use App\Http\Controllers\Admin\PledgeController as AdminPledge;
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
    
    // Installment Plans Routes
    Route::get('/installments', [NasabahInstallmentPlan::class, 'index'])->name('installments.index');
    
    // Gold Pledge Routes
    Route::get('/pledges', [NasabahPledge::class, 'index'])->name('pledges.index');
    Route::get('/pledges/create', [NasabahPledge::class, 'create'])->name('pledges.create');
    Route::post('/pledges/calculate', [NasabahPledge::class, 'calculate'])->name('pledges.calculate');
    Route::post('/pledges', [NasabahPledge::class, 'store'])->name('pledges.store');
    Route::get('/pledges/{id}', [NasabahPledge::class, 'show'])->name('pledges.show');
    Route::get('/pledges/{id}/payment', [NasabahPledge::class, 'payment'])->name('pledges.payment');
    Route::post('/pledges/{id}/payment', [NasabahPledge::class, 'processPayment'])->name('pledges.payment.process');
    Route::get('/pledges/{id}/redeem', [NasabahPledge::class, 'redeem'])->name('pledges.redeem');
    Route::post('/pledges/{id}/redeem', [NasabahPledge::class, 'processRedeem'])->name('pledges.redeem.process');
    Route::get('/installments/create', [NasabahInstallmentPlan::class, 'create'])->name('installments.create');
    Route::post('/installments/calculate', [NasabahInstallmentPlan::class, 'calculate'])->name('installments.calculate');
    Route::post('/installments', [NasabahInstallmentPlan::class, 'store'])->name('installments.store');
    Route::get('/installments/{id}', [NasabahInstallmentPlan::class, 'show'])->name('installments.show');
    Route::get('/installments/{id}/payment', [NasabahInstallmentPlan::class, 'payment'])->name('installments.payment');
    Route::post('/installments/{id}/payment', [NasabahInstallmentPlan::class, 'processPayment'])->name('installments.payment.process');
    Route::get('/installments/{id}/early-payment', [NasabahInstallmentPlan::class, 'earlyPayment'])->name('installments.early-payment');
    Route::post('/installments/{id}/early-payment', [NasabahInstallmentPlan::class, 'processEarlyPayment'])->name('installments.early-payment.process');
    Route::get('/installments/{id}/cancel', [NasabahInstallmentPlan::class, 'cancel'])->name('installments.cancel');
    Route::post('/installments/{id}/cancel', [NasabahInstallmentPlan::class, 'processCancel'])->name('installments.cancel.process');
    
    // Gold Pledge Routes (Placeholder)
    Route::get('/pledges', [NasabahPledge::class, 'index'])->name('pledges.index');
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
    
    // Installment Plans Management
    Route::get('/installments', [AdminInstallmentPlan::class, 'index'])->name('installments.index');
    Route::get('/installments/{id}', [AdminInstallmentPlan::class, 'show'])->name('installments.show');
    Route::post('/installments/payments/{paymentId}/verify', [AdminInstallmentPlan::class, 'verifyPayment'])->name('installments.payments.verify');
    Route::post('/installments/payments/{paymentId}/reject', [AdminInstallmentPlan::class, 'rejectPayment'])->name('installments.payments.reject');
    
    // Gold Pledges Management
    Route::get('/pledges', [AdminPledge::class, 'index'])->name('pledges.index');
    Route::get('/pledges/{id}', [AdminPledge::class, 'show'])->name('pledges.show');
    Route::post('/pledges/{id}/approve', [AdminPledge::class, 'approve'])->name('pledges.approve');
    Route::post('/pledges/{id}/reject', [AdminPledge::class, 'reject'])->name('pledges.reject');
    Route::post('/pledges/payments/{paymentId}/verify', [AdminPledge::class, 'verifyPayment'])->name('pledges.payments.verify');
    Route::post('/pledges/payments/{paymentId}/reject', [AdminPledge::class, 'rejectPayment'])->name('pledges.payments.reject');
    Route::post('/pledges/extensions/{extensionId}/approve', [AdminPledge::class, 'approveExtension'])->name('pledges.extensions.approve');
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
