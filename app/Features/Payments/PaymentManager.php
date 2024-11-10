<?php

namespace App\Features\Payments;

use App\Features\Payments\Contracts\PaymentDriver;
use App\Features\Payments\Services\CurlecAPI;
use App\Features\Payments\Services\CurlecDriver;
use App\Features\Payments\Services\StripeDriver;
use Illuminate\Support\Manager;

class PaymentManager extends Manager
{
    public function createCurlecDriver(): PaymentDriver
    {
        return new CurlecDriver(new CurlecAPI);
    }

    public function createStripeDriver(): PaymentDriver
    {
        return new StripeDriver;
    }

    public function getDefaultDriver()
    {
        return config('payment.driver', 'curlec');
    }
}
