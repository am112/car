<?php

use Illuminate\Support\Facades\Route;

Route::redirect('', '/login');

Route::middleware('auth')->group(function () {

    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('customers')
        ->controller(App\Http\Controllers\CustomerController::class)
        ->group(function () {
            Route::get('', 'index')->name('customers.index');
            Route::get('{customer}', 'show')->name('customers.show');
            Route::patch('{customer}', 'update')->name('customers.update');
            Route::get('{customer}/invoices', 'invoices')->name('customers.invoices');
        });

    Route::prefix('invoices')
        ->controller(App\Http\Controllers\InvoiceController::class)
        ->group(function () {
            Route::get('', 'index')->name('invoices.index');
        });

    Route::prefix('users')
        ->controller(App\Http\Controllers\UserController::class)
        ->group(function () {
            Route::get('', 'index')->name('users.index');
            Route::post('', 'store')->name('users.store');
            Route::get('create', 'create')->name('users.create');

        });

    Route::prefix('profile')
        ->controller(App\Http\Controllers\ProfileController::class)
        ->group(function () {
            Route::get('profile', 'edit')->name('profile.edit');
            Route::patch('profile', 'update')->name('profile.update');
            Route::delete('profile', 'destroy')->name('profile.destroy');

        });

});

require __DIR__ . '/auth.php';
