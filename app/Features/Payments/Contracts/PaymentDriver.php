<?php

namespace App\Features\Payments\Contracts;

interface PaymentDriver
{
    /**
     * Summary of create
     */
    public function create(array $payload): mixed;

    /**
     * Summary of processPayment
     */
    public function process(array $payload);
}
