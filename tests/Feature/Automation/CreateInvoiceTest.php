<?php

use App\Features\Automation\Events\ProcessInvoice;
use App\Features\Automation\Services\CreateCharges;
use App\Features\Automation\Services\CreateInvoices;
use App\Features\Automation\Services\ProcessPayments;
use App\Models\Invoice;
use App\Models\Order;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

it('automation: job createInvoice event can be dispatch', function (): void {
    Event::fake();

    $order = Order::factory()->create();

    $invoiceDate = Carbon::parse($order->contract_at);

    resolve(CreateInvoices::class)->create($order, $invoiceDate);

    Event::assertDispatched(ProcessInvoice::class);
});

it('automation: job can create one invoice per order', function (): void {

    $order = Order::factory()->create();

    $invoiceDate = Carbon::parse($order->contract_at);

    resolve(CreateInvoices::class)->create($order, $invoiceDate);

    $invoiceCount = $order->invoices()->count();

    expect($invoiceCount)->toBe(1);
});

it('automation: job can create N(tenure) numbers of inoices per order', function (): void {
    $order = Order::factory()->create();

    $invoiceDate = Carbon::parse($order->contract_at);

    $contractEndDate = Carbon::parse($order->contract_at)->addMonths($order->tenure + 1);

    $serviceContainer = resolve(CreateInvoices::class);

    while ($invoiceDate->lte($contractEndDate)) {
        $serviceContainer->handle($invoiceDate);
        $invoiceDate->addDay();
    }

    $invoiceCount = $order->invoices()->count();

    expect($invoiceCount)->toBe($order->tenure);
});

it('automation: new invoice must return status pending', function (): void {

    $order = Order::factory()->create();

    $invoiceDate = Carbon::parse($order->contract_at);

    resolve(CreateInvoices::class)->create($order, $invoiceDate);

    $invoice = $order->invoices()->first();

    expect($invoice->status)->toBe(Invoice::STATUS_PENDING);
});

it('automation: invoice amount should be same as order monthly amount', function (): void {

    $order = Order::factory()->create();

    $invoiceDate = Carbon::parse($order->contract_at);

    resolve(CreateInvoices::class)->create($order, $invoiceDate);

    $invoice = $order->invoices()->first();

    expect($invoice->unresolved_amount)->toBe($order->subscription_fee);
});

it('automation: invoice should be charge (late)', function (): void {

    $order = Order::factory()->create();

    $serviceContainer = resolve(CreateInvoices::class);

    // first invoice
    $serviceContainer->create($order, Carbon::parse($order->contract_at));

    $runningAt = Carbon::parse($order->contract_at);

    // next invoice
    $invoiceDate = Carbon::parse($order->contract_at)->addMonth();

    $chargeService = resolve(CreateCharges::class);
    while ($runningAt->lte($invoiceDate)) {
        $chargeService->handle($runningAt);
        $runningAt->addDay();
    }

    $serviceContainer->create($order, $invoiceDate);

    $invoice = $order->invoices()->orderBy('id', 'desc')->first();

    expect($invoice->charge_fee)->toBeGreaterThan(0);
});

it('automation: invoice should not be charge (late)', function (): void {

    $order = Order::factory()->create();

    $invoiceService = resolve(CreateInvoices::class);

    // first invoice
    $invoiceService->create($order, Carbon::parse($order->contract_at));

    // simulate payment
    $data = [
        'customer_id' => $order->customer_id,
        'reference_no' => Helper::referenceNoConvention('PAY', mt_rand(1, 9999), today()),
        'amount' => $order->subscription_fee,
        'paid_at' => $order->contract_at,
        'unresolved' => true,
        'unresolved_amount' => $order->subscription_fee,
    ];

    $order->payments()->create($data);

    $runningAt = Carbon::parse($order->contract_at);

    // next invoice
    $invoiceDate = Carbon::parse($order->contract_at)->addMonth();

    while ($runningAt->lte($invoiceDate)) {
        (new ProcessPayments)->handle();
        (new CreateCharges)->handle($runningAt);
        $runningAt->addDay();
    }

    $invoiceService->create($order, $invoiceDate);

    $invoice = $order->invoices()->orderBy('id', 'desc')->first();

    expect($invoice->charge_fee)->toBe(0);
});

it('automation: invoice should be charge (late: 1000)', function (): void {

    $order = Order::factory()->create();

    $invoiceService = resolve(CreateInvoices::class);

    // first invoice
    $invoiceService->create($order, Carbon::parse($order->contract_at));

    // simulate payment
    $data = [
        'customer_id' => $order->customer_id,
        'reference_no' => Helper::referenceNoConvention('PAY', mt_rand(1, 9999), today()),
        'amount' => $order->subscription_fee,
        'paid_at' => $order->contract_at,
        'unresolved' => true,
        'unresolved_amount' => $order->subscription_fee,
    ];

    $runningAt = Carbon::parse($order->contract_at);

    // next invoice
    $invoiceDate = Carbon::parse($order->contract_at)->addMonth();

    while ($runningAt->lte($invoiceDate)) {
        if (Carbon::parse($order->contract_at)->diffInDays($runningAt) == 14) {
            $order->payments()->create($data);
        }
        (new CreateCharges)->handle($runningAt);

        (new ProcessPayments)->handle();
        $runningAt->addDay();
    }

    $invoiceService->create($order, $invoiceDate);

    $invoice = $order->invoices()->with('charges')->orderBy('id', 'desc')->first();

    expect($invoice->charge_fee)->toBe(1000);
});
