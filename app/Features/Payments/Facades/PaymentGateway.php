<?php

namespace App\Features\Payments\Facades;

use App\Features\Payments\PaymentManager;
use Illuminate\Support\Facades\Facade;

class PaymentGateway extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PaymentManager::class;
    }
}
