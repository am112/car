<?php

namespace App\Features\Automation\Listeners;

use App\Features\Automation\Events\ProcessInvoice;
use App\Features\Automation\Events\ProcessPayment;
use App\Models\Invoice;
use App\Models\Payment;

class PaymentResolver
{
    /**
     * Handle the event.
     */
    public function handle(ProcessInvoice|ProcessPayment $event): void
    {

        $payments = $event->order->payments()->unresolved()->get();
        $invoice = $event->invoice->fresh();

        foreach ($payments as $payment) {

            $balancePayment = $payment->unresolved_amount - $invoice->unresolved_amount;

            if ($balancePayment > 0) {

                $this->updatePayment($payment, [
                    'unresolved_amount' => $balancePayment,
                ]);

                $this->attachToInvoice($invoice, $payment, $invoice->unresolved_amount);

                $this->updateInvoice($invoice, [
                    'unresolved' => false,
                    'unresolved_amount' => 0,
                    'status' => Invoice::STATUS_PAID,
                ]);

                break;
            }

            $amountCharged = $payment->unresolved_amount;

            $this->updatePayment($payment, [
                'unresolved' => false,
                'unresolved_amount' => 0,
            ]);

            if ($balancePayment === 0) {

                $this->attachToInvoice($invoice, $payment, $amountCharged);

                $this->updateInvoice($invoice, [
                    'unresolved' => false,
                    'unresolved_amount' => 0,
                    'status' => Invoice::STATUS_PAID,
                ]);

                break;
            }

            $this->updateInvoice($invoice, [
                'unresolved_amount' => abs($balancePayment),
                'status' => Invoice::STATUS_PARTIAL_PAID,
            ]);

            $this->attachToInvoice($invoice, $payment, $amountCharged);
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
     * Summary of updatePayment
     */
    private function updatePayment(Payment $payment, array $data): void
    {
        $payment->update($data);
    }

    /**
     * Summary of attachToInvoice
     */
    private function attachToInvoice(Invoice $invoice, Payment $payment, int $amount): void
    {
        $invoice->payments()->attach($payment, ['amount' => $amount, 'created_at' => now(), 'updated_at' => now()]);
    }
}
