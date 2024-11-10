<?php

namespace Database\Factories;

use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $payments = ['stripe', 'paypal'];
        $tenure = [12, 24, 36];
        $contractDate = Carbon::parse('2021-01-01')->addDays($this->faker->numberBetween(0, 365));

        return [
            'customer_id' => CustomerFactory::new(),
            'reference_no' => Helper::referenceNoConvention('OR', fake()->numberBetween(1, 1000), $contractDate),
            'tenure' => $tenure[mt_rand(0, count($tenure) - 1)],
            'subscription_fee' => fake()->numberBetween(1000, 10000),
            'contract_at' => $contractDate,
            'payment_gateway' => $payments[mt_rand(0, count($payments) - 1)],
            'payment_reference' => fake()->uuid(),
        ];
    }
}
