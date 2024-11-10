<?php

namespace App\Features\Automation\Listeners;

use App\Features\Automation\Events\ProcessInvoice;
use App\Models\Credit;
use App\Models\Invoice;

class CreditResolver
{
    /**
     * Handle the event.
     */
    public function handle(ProcessInvoice $event): void
    {

        $credits = $event->order->credits()->unresolved()->get();
        $invoice = $event->invoice->fresh();

        foreach ($credits as $credit) {

            $unresolvedInvoiceAmount = $invoice->unresolved_amount;
            $unresolvedCreditAmount = $credit->unresolved_amount;

            $balanceCredit = $credit->unresolved_amount - $invoice->unresolved_amount;

            if ($balanceCredit > 0) {

                $credit->unresolved_amount = $balanceCredit;
                $credit->save();

                $this->updateInvoice($invoice, [
                    'credit_paid' => $unresolvedInvoiceAmount,
                    'unresolved' => false,
                    'unresolved_amount' => 0,
                    'status' => Invoice::STATUS_PAID,
                ]);
                $this->attachToInvoice($invoice, $credit);

                break;
            }

            $credit->unresolved = false;
            $credit->unresolved_amount = 0;
            $credit->save();

            if ($balanceCredit === 0) {

                $this->updateInvoice($invoice, [
                    'credit_paid' => $unresolvedInvoiceAmount,
                    'unresolved' => false,
                    'unresolved_amount' => 0,
                    'status' => Invoice::STATUS_PAID,
                ]);

                $this->attachToInvoice($invoice, $credit);

                break;
            }

            $this->updateInvoice($invoice, [
                'credit_paid' => $unresolvedCreditAmount,
                'unresolved_amount' => abs($balanceCredit),
            ]);

            $this->attachToInvoice($invoice, $credit);
        }
    }

    /**
     * Summary of updateInvoice
     */
    private function updateInvoice(Invoice $invoice, array $data): void
    {
        $invoice->update($data);
    }

    /**
     * Summary of attachToInvoice
     */
    private function attachToInvoice(Invoice $invoice, Credit $credit): void
    {
        $invoice->credits()->attach($credit, ['amount' => ($credit->amount - $credit->unresolved_amount), 'created_at' => now(), 'updated_at' => now()]);
    }
}
