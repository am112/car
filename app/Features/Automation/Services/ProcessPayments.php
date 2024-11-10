<?php

namespace App\Features\Automation\Services;

use App\Features\Automation\Events\ProcessPayment;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class ProcessPayments
{
    public function handle(): void
    {
        // select orders where has unresolved payments
        $unresolvedOrderIds = Payment::query()
            ->unresolved()
            ->pluck('order_id');

        // get olders invoice first
        $invoices = Invoice::query()
            ->withSum('payments', 'invoice_payment.amount')
            ->with('order')
            ->unresolved()
            ->whereIn('order_id', $unresolvedOrderIds)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($invoices as $invoice) {
            DB::transaction(function () use ($invoice): void {

                ProcessPayment::dispatch($invoice->order, $invoice);

            });

        }
    }
}
