<?php

namespace App\Features\Automation\Services;

use App\Features\Automation\Events\ProcessInvoice;
use App\Features\Automation\Utils\AutomationUtil;
use App\Models\Invoice;
use App\Models\Order;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CreateInvoices
{
    /**
     * Summary of handle
     */
    public function handle(Carbon $runningAt): void
    {

        $allowedAt = AutomationUtil::nextInvoiceCreationDate($runningAt);

        Order::query()
            ->runnableOn($allowedAt)
            ->whereNull('completed_at')
            ->chunk(100, function (Collection $orders) use ($runningAt): void {
                foreach ($orders as $order) {
                    $this->create($order, $runningAt);
                }
            });
    }

    public function create(Order $order, Carbon $runningAt): void
    {
        // return if running date < contract date
        if ($runningAt->lt($order->contract_at)) {
            return;
        }

        // return if total invoices >= tenure
        if ($order->invoices()->count() >= $order->tenure) {
            return;
        }

        // return if invoice created
        $exists = $order->invoices()
            ->where('issue_at', $runningAt->format('Y-m-d'))
            ->exists();

        if ($exists) {
            return;
        }

        DB::transaction(function () use ($order, $runningAt): void {
            // create the invoice
            $invoice = Invoice::create([
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'reference_no' => time(),
                'issue_at' => $runningAt->format('Y-m-d'),
                'due_at' => Carbon::parse($runningAt)->addDay()->format('Y-m-d'),
                'subscription_fee' => $order->subscription_fee,
                'status' => Invoice::STATUS_PENDING,
                'unresolved_amount' => $order->subscription_fee,
            ]);

            $invoice->update([
                'reference_no' => Helper::referenceNoConvention(Invoice::PREFIX, $invoice->id, $runningAt),
            ]);

            // call event
            ProcessInvoice::dispatch($order, $invoice);

            // set completed
            if ($order->invoices()->count() >= $order->tenure) {
                $order->update([
                    'completed_at' => $runningAt->format('Y-m-d'),
                ]);
            }
        });
    }
}
