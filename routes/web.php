<?php

use App\Features\Payments\Facades\PaymentGateway;
use Illuminate\Http\Request;
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
            Route::get('{invoice}', 'show')->name('invoices.show');
        });

    Route::prefix('users')
        ->controller(App\Http\Controllers\UserController::class)
        ->group(function () {
            Route::get('', 'index')->name('users.index');
        });

    Route::prefix('profile')
        ->controller(App\Http\Controllers\ProfileController::class)
        ->group(function () {
            Route::get('', 'edit')->name('profile.edit');
            Route::patch('', 'update')->name('profile.update');
            Route::delete('', 'destroy')->name('profile.destroy');

        });

});

require __DIR__ . '/auth.php';

Route::get('curlec-instant-pay', function () {
    return PaymentGateway::driver('curlec')->create([]);
});

Route::get('curlec-instant-pay-response', function (Request $request) {

    PaymentGateway::driver('curlec')->process($request->all());

    return 'OK';
});
