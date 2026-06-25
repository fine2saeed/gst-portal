<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BusinessProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\ClientController;
use Illuminate\Support\Facades\Route;

// ─── Auth Routes (Breeze) ────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── Authenticated Routes ─────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Business Profile
    Route::get('/profile/business', [BusinessProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/business', [BusinessProfileController::class, 'update'])->name('profile.update');

    // Customers
    Route::resource('customers', CustomerController::class)->except(['show']);

    // Products
    Route::resource('products', ProductController::class)->except(['show']);

    // Invoices
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/pdf',    [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::post('/invoices/{invoice}/email', [InvoiceController::class, 'sendEmail'])->name('invoices.email');
    Route::post('/invoices/{invoice}/cancel',[InvoiceController::class, 'cancel'])->name('invoices.cancel');

    // Invoice Payments
    Route::post('/invoices/{invoice}/payments', [InvoicePaymentController::class, 'store'])->name('invoices.payments.store');
    Route::delete('/invoices/{invoice}/payments/{payment}', [InvoicePaymentController::class, 'destroy'])->name('invoices.payments.destroy');

    // Reports (non-super-admin only)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

// ─── Super Admin Routes ───────────────────────────────────────────────────────
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('clients', ClientController::class);
});
