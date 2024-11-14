<?php

namespace App\Features\Payments\Services;

use App\Features\Payments\Api\CurlecAPI;
use App\Features\Payments\Contracts\PaymentDriver;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;

class CurlecDriver implements PaymentDriver
{
    private const CURLEC_CHANNEL = 'curlec';
    private const PAYMENT_CHANNEL = 'payments';

    /**
     * Summary of api
     */
    private CurlecAPI $api;

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->api = resolve(CurlecAPI::class);
    }

    /**
     * Summary of create
     */
    public function create(array $payload): string
    {
        $link = $this->api->generateInstantPayment($payload);

        return $link;
    }

    /**
     * Summary of process
     */
    public function process(array $payload): void
    {
        // storing record
        Log::channel(self::CURLEC_CHANNEL)->info('instant-payment:', $payload);

        Log::channel(self::PAYMENT_CHANNEL)->info('instant-payment payload:', $payload);

        $result = $this->api->validateInstantPaymentChecksum($payload, $payload['fpx_checksum']);

        if (! $result) {

            Log::channel(self::PAYMENT_CHANNEL)->info('Invalid checksum');

            return;
        }

        $order = Order::query()
            ->where('reference_no', $payload['fpx_sellerOrderNo'])
            ->first();

        if (! $order) {

            Log::channel(self::PAYMENT_CHANNEL)->info('Order not found: ' . $payload['fpx_sellerOrderNo']);

            return;
        }

        $payment = $order->payments()
            ->where('reference_no', $payload['fpx_fpxTxnId'])
            ->exists();

        if ($payment) {

            Log::channel(self::PAYMENT_CHANNEL)->info('Payment already exists: ' . $payload['fpx_fpxTxnId']);

            return;
        }

        // $c = Carbon::createFromFormat('D M d H:i:s Y', 'Thu Nov 14 19:48:42 MYR 2024');

        try {

            $order->payments()
                ->create([
                    'customer_id' => $order->customer_id,
                    'reference_no' => $payload['fpx_fpxTxnId'],
                    'paid_at' => now(),
                    'amount' => (float) $payload['fpx_txnAmount'] * 100,
                    'unresolved' => true,
                    'unresolved_amount' => (float) $payload['fpx_txnAmount'] * 100,
                ]);

            Log::channel(self::PAYMENT_CHANNEL)->info('payment created: ' . $payload['fpx_fpxTxnId']);

        } catch (Exception $e) {

            Log::channel(self::PAYMENT_CHANNEL)->error('Error creating payment: ', ['error' => $e->getMessage()]);

        }

    }

    public function generateCollection()
    {
        $list = [
            [
                'refNum' => 'Zhq-ccY-gDi',
                'amount' => 10.00,
                'items' => [
                    [
                        'item' => 'INV-0001',
                        'amount' => 10.00,
                        'customerUID' => '1',
                        'description' => 'collection test',
                    ],
                ],
            ],
        ];
        $this->api->generateCollection(now()->format('d/m/Y H:i:s'), $list);
    }
}
