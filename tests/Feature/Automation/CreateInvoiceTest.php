<?php

use App\Features\Automation\Events\ProcessInvoice;
use App\Features\Automation\Services\CreateCharges;
use App\Features\Automation\Services\CreateInvoices;
use App\Features\Automation\Services\ProcessPayments;
use App\Features\Payments\Facades\PaymentGateway;
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

    (new CreateInvoices)->create($order, $invoiceDate);

    Event::assertDispatched(ProcessInvoice::class);
});

it('automation: job can create one invoice per order', function (): void {

    $order = Order::factory()->create();

    $invoiceDate = Carbon::parse($order->contract_at);

    (new CreateInvoices)->create($order, $invoiceDate);

    $invoiceCount = $order->invoices()->count();

    expect($invoiceCount)->toBe(1);
});

it('automation: job can create N(tenure) numbers of inoices per order', function (): void {
    $order = Order::factory()->create();

    $invoiceDate = Carbon::parse($order->contract_at);

    $contractEndDate = Carbon::parse($order->contract_at)->addMonths($order->tenure + 1);

    while ($invoiceDate->lte($contractEndDate)) {
        (new CreateInvoices)->handle($invoiceDate);
        $invoiceDate->addDay();
    }

    $invoiceCount = $order->invoices()->count();

    expect($invoiceCount)->toBe($order->tenure);
});

it('automation: new invoice must return status pending', function (): void {

    $order = Order::factory()->create();

    $invoiceDate = Carbon::parse($order->contract_at);

    (new CreateInvoices)->create($order, $invoiceDate);

    $invoice = $order->invoices()->first();

    expect($invoice->status)->toBe(Invoice::STATUS_PENDING);
});

it('automation: invoice amount should be same as order monthly amount', function (): void {

    $order = Order::factory()->create();

    $invoiceDate = Carbon::parse($order->contract_at);

    (new CreateInvoices)->create($order, $invoiceDate);

    $invoice = $order->invoices()->first();

    expect($invoice->unresolved_amount)->toBe($order->subscription_fee);
});

it('automation: invoice should be charge (late)', function (): void {

    $order = Order::factory()->create();

    // first invoice
    (new CreateInvoices)->create($order, Carbon::parse($order->contract_at));

    $runningAt = Carbon::parse($order->contract_at);

    // next invoice
    $invoiceDate = Carbon::parse($order->contract_at)->addMonth();

    while ($runningAt->lte($invoiceDate)) {
        (new CreateCharges)->handle($runningAt);
        $runningAt->addDay();
    }

    (new CreateInvoices)->create($order, $invoiceDate);

    $invoice = $order->invoices()->orderBy('id', 'desc')->first();

    expect($invoice->charge_fee)->toBeGreaterThan(0);
});

it('automation: invoice should not be charge (late)', function (): void {

    $order = Order::factory()->create();

    // first invoice
    (new CreateInvoices)->create($order, Carbon::parse($order->contract_at));

    // simulate payment
    $data = [
        'reference_no' => Helper::referenceNoConvention('PAY', mt_rand(1, 9999), today()),
        'amount' => $order->subscription_fee,
        'payment_reference' => $order->payment_reference,
        'paid_at' => $order->contract_at,
    ];

    PaymentGateway::driver($order->payment_gateway)->process($data);

    $runningAt = Carbon::parse($order->contract_at);

    // next invoice
    $invoiceDate = Carbon::parse($order->contract_at)->addMonth();

    while ($runningAt->lte($invoiceDate)) {
        (new ProcessPayments)->handle();
        (new CreateCharges)->handle($runningAt);
        $runningAt->addDay();
    }

    (new CreateInvoices)->create($order, $invoiceDate);

    $invoice = $order->invoices()->orderBy('id', 'desc')->first();

    expect($invoice->charge_fee)->toBe(0);
});

it('automation: invoice should be charge (late: 1000)', function (): void {

    $order = Order::factory()->create();

    // first invoice
    (new CreateInvoices)->create($order, Carbon::parse($order->contract_at));

    // simulate payment
    $data = [
        'reference_no' => Helper::referenceNoConvention('PAY', mt_rand(1, 9999), today()),
        'amount' => $order->subscription_fee,
        'payment_reference' => $order->payment_reference,
        'paid_at' => $order->contract_at,
    ];

    $runningAt = Carbon::parse($order->contract_at);

    // next invoice
    $invoiceDate = Carbon::parse($order->contract_at)->addMonth();

    while ($runningAt->lte($invoiceDate)) {
        if (Carbon::parse($order->contract_at)->diffInDays($runningAt) == 14) {
            info('should enter here: ' . $runningAt->format('Y-m-d') . ': ' . $order->contract_at);
            PaymentGateway::driver($order->payment_gateway)->process($data);
        }
        (new CreateCharges)->handle($runningAt);

        (new ProcessPayments)->handle();
        $runningAt->addDay();
    }

    (new CreateInvoices)->create($order, $invoiceDate);

    $invoice = $order->invoices()->with('charges')->orderBy('id', 'desc')->first();

    expect($invoice->charge_fee)->toBe(1000);
});
