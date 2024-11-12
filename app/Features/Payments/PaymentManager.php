<?php

namespace App\Features\Payments;

use App\Features\Payments\Contracts\PaymentDriver;
use App\Features\Payments\Services\CurlecDriver;
use App\Features\Payments\Services\StripeDriver;
use Illuminate\Support\Manager;

class PaymentManager extends Manager
{
    public function createCurlecDriver(): PaymentDriver
    {
        return new CurlecDriver;
    }

    public function createStripeDriver(): PaymentDriver
    {
        return new StripeDriver;
    }

    public function getDefaultDriver()
    {
        return config('services.payment.default');
    }
}
