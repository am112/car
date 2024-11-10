<?php

namespace App\Features\Automation\Events;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessPayment
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order, public Invoice $invoice)
    {

        // listener class

        // 3. resolved untag/overpaid payments
    }
}
