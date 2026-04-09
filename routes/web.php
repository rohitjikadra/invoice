<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryChallanController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingsController;


Route::get('/', fn () => redirect()->route('invoices.index'));

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/password/change', [AuthController::class, 'showChangePasswordForm'])->name('password.edit');
    Route::put('/password', [AuthController::class, 'updatePassword'])->name('password.update');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/preview', [InvoiceController::class, 'previewPdf'])->name('invoices.preview');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'downloadPdf'])->name('invoices.download');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    Route::get('/delivery-challans', [DeliveryChallanController::class, 'index'])->name('delivery-challans.index');
    Route::get('/delivery-challans/create', [DeliveryChallanController::class, 'create'])->name('delivery-challans.create');
    Route::post('/delivery-challans', [DeliveryChallanController::class, 'store'])->name('delivery-challans.store');
    Route::get('/delivery-challans/{deliveryChallan}', [DeliveryChallanController::class, 'show'])->name('delivery-challans.show');
    Route::get('/delivery-challans/{deliveryChallan}/edit', [DeliveryChallanController::class, 'edit'])->name('delivery-challans.edit');
    Route::put('/delivery-challans/{deliveryChallan}', [DeliveryChallanController::class, 'update'])->name('delivery-challans.update');
    Route::delete('/delivery-challans/{deliveryChallan}', [DeliveryChallanController::class, 'destroy'])->name('delivery-challans.destroy');
    Route::get('/delivery-challans/{deliveryChallan}/preview', [DeliveryChallanController::class, 'preview'])->name('delivery-challans.preview');
    Route::get('/delivery-challans/{deliveryChallan}/download', [DeliveryChallanController::class, 'download'])->name('delivery-challans.download');
});
