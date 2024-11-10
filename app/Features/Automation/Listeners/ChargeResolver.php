<?php

namespace App\Features\Automation\Listeners;

use App\Features\Automation\Events\ProcessInvoice;

class ChargeResolver
{
    /**
     * Handle the event.
     */
    public function handle(ProcessInvoice $event): void
    {

        $charges = $event->order->charges()->unresolved()->get();
        $invoice = $event->invoice->fresh();

        if ($charges->sum('amount') === 0) {
            return;
        }

        $invoice->update([
            'charge_fee' => $charges->sum('amount'),
            'unresolved_amount' => $invoice->unresolved_amount + $charges->sum('amount'),
        ]);

        $event->order->charges()
            ->whereIn('id', $charges->pluck('id'))
            ->update([
                'unresolved' => false,
                'invoice_id' => $invoice->id,
            ]);
    }
}
