<?php

namespace App\Features\Automation\Listeners;

use App\Features\Automation\Events\ProcessInvoice;
use App\Mail\NotifyInvoiceCreated;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class InvoiceNotification implements ShouldQueueAfterCommit
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(ProcessInvoice $event): void
    {
        $invoice = $event->invoice->fresh();

        // send email to customer
        // Mail::to($invoice->customer->email)
        //     ->send(new NotifyInvoiceCreated($invoice));

    }
}
