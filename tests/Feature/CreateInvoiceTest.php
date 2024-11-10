<?php

use App\Features\Automation\Actions\CreateInvoices;
use App\Features\Automation\Events\ProcessInvoice;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;

it('job are able to create invoice per order', function (): void {

    Event::fake();
    $order = Order::factory()->create();

    $runningAt = Carbon::parse($order->contract_at)->addMonth();
    (new CreateInvoices)->handle($runningAt);

    Event::assertDispatched(ProcessInvoice::class);
});
