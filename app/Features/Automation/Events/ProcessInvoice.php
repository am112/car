<?php

namespace App\Features\Automation\Events;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessInvoice
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order, public Invoice $invoice)
    {

        // listener class

        // 1. resolved untag charges

        // 2. resolved untag credits

        // 3. resolved untag/overpaid payments

        // TODO 4. create transactional table

        // TODO 5. create pdf invoices

        // 6. send invoices
    }
}
