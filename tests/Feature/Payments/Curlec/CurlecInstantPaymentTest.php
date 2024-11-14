<?php

use App\Features\Payments\Facades\PaymentGateway;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can generate instant payment link', function (): void {
    $order = Order::factory()->create();

    $link = PaymentGateway::driver('curlec')->create([
        'orderNo' => $order->reference_no,
        'description' => 'Test Instant Payment',
        'amount' => $order->convertToDecimal($order->subscription_fee),
        'bankCode' => 'TEST0021',
        'businessModel' => 'B2C',
    ]);

    expect($link)->toBeString();

});

it('can process instant payment', function (): void {
    $order = Order::factory()->create();
    $order->reference_no = 'OR-210520-0631';
    $order->save();

    $response = $this->get('/curlec-instant-pay-response?curlec_method=01&fpx_fpxTxnId=2411142126320398&fpx_sellerExOrderNo=184625123&fpx_fpxTxnTime=Thu%20Nov%2014%2021:26:32%20MYT%202024&fpx_sellerOrderNo=OR-210520-0631&fpx_sellerId=SE00009436&fpx_txnCurrency=MYR&fpx_txnAmount=57.95&fpx_buyerName=PnN%40m%28%29%2FPyN.-%26B%27%27UYER&fpx_buyerBankId=TEST0021&fpx_debitAuthCode=00&fpx_type=N&payment_method=FPX&fpx_description=Test+Instant+Payment&fpx_notes=&fpx_checksum=2f024364c1c3d81a9242a36f7143be87f46740b5758f883007147e6125835db8');

    $response->assertOk();
    expect($order->payments()->count())->toBe(1);

});
