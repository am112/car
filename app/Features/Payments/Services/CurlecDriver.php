<?php

namespace App\Features\Payments\Services;

use App\Features\Payments\Contracts\PaymentDriver;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class CurlecDriver implements PaymentDriver
{
    public function __construct(public CurlecAPI $api) {}

    /**
     * Summary of create
     */
    public function create(array $payload): void
    {
        Log::channel('payments')->info('Create payment using curlec');
    }

    /**
     * Summary of process
     */
    public function process(array $payload): void
    {

        $order = Order::query()
            ->where('payment_reference', $payload['payment_reference'])
            ->first();

        if (! $order) {
            abort(400, 'Order not found');
        }

        $order->payments()->create([
            'customer_id' => $order->customer_id,
            'reference_no' => $payload['reference_no'],
            'paid_at' => $payload['paid_at'],
            'amount' => $payload['amount'],
            'unresolved' => true,
            'unresolved_amount' => $payload['amount'],
        ]);

        Log::channel('payments')->info('Process payment using curlec');

    }
}
