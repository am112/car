<?php

use App\Features\Automation\Services\CreateInvoices;
use App\Features\Payments\Facades\PaymentGateway;
use App\Models\Order;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('payment:tag', function (App\Features\Automation\Services\ProcessPayments $service) {
    $service->handle();
});

Artisan::command('simulate:process-payment', function () {
    $order = Order::first();

    $data = [
        'reference_no' => Helper::referenceNoConvention('PAY', mt_rand(1, 9999), today()),
        'amount' => $order->subscription_fee + mt_rand(100, 4000),
        'payment_reference' => $order->payment_reference,
        'paid_at' => now(),
    ];

    PaymentGateway::driver('curlec')->process($data);
});

Artisan::command('simulation:manual-invoice', function (CreateInvoices $action) {
    $order = Order::first();
    $runningAt = Carbon::parse('2024-04-03');

    $action->create($order, $runningAt);
});
