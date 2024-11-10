<?php

namespace App\Features\Payments\Services;

use App\Features\Payments\Contracts\PaymentDriver;
use Illuminate\Support\Facades\Log;

class StripeDriver implements PaymentDriver
{
    /**
     * Summary of create
     */
    public function create(array $payload): void
    {
        Log::channel('payments')->info('Create payment using stripe');
    }

    /**
     * Summary of processPayment
     */
    public function process(array $payload): void
    {
        Log::channel('payments')->info('Process payment using stripe');
    }
}
