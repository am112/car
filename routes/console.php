<?php

use App\Features\Automation\Services\CreateCharges;
use App\Features\Automation\Services\CreateInvoices;
use App\Features\Automation\Services\ProcessPayments;
use App\Features\Payments\Facades\PaymentGateway;
use App\Features\Payments\Services\CurlecDriver;
use App\Models\Order;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// =========================== simulation testing command ===============================

Artisan::command('simulate:create-invoices', function (CreateInvoices $createInvoices, CreateCharges $createCharges): void {

    Order::factory()->count(1)->create();

    $firstOrder = Order::query()
        ->orderBy('contract_at', 'ASC')
        ->first();

    $runningAt = Carbon::parse($firstOrder->contract_at);

    while ($runningAt->lte(today())) {

        $createCharges->handle($runningAt);

        $createInvoices->handle($runningAt);

        $runningAt->addDay();

        usleep(100);
    }

});

Artisan::command('simulate:create-invoice', function (CreateInvoices $action): void {

    $order = Order::first();

    $runningAt = Carbon::parse('2024-04-03');

    $action->create($order, $runningAt);

});

Artisan::command('simulate:process-payment', function (): void {

    $order = Order::first();

    $data = [
        'reference_no' => Helper::referenceNoConvention('PAY', mt_rand(1, 9999), today()),
        'amount' => $order->subscription_fee + mt_rand(100, 4000),
        'payment_reference' => $order->payment_reference,
        'paid_at' => now(),
    ];

    PaymentGateway::driver('curlec')->process($data);

});

Artisan::command('simulate:payment-tagging', function (ProcessPayments $service): void {

    $service->handle();

});

Artisan::command('simulate:curlec-collection', function (CurlecDriver $driver) {
    $driver->generateCollection();
});
