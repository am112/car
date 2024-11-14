<?php

namespace App\Features\Payments\Api;

use App\Features\Payments\Api\Traits\WithChecksum;
use Illuminate\Support\Facades\Http;

class CurlecAPI
{
    use WithChecksum;

    /**
     * Summary of merchantId
     */
    private string $merchantId;

    /**
     * Summary of merchantKey
     */
    private string $merchantKey;

    /**
     * Summary of employeeId
     */
    private string $employeeId;

    /**
     * Summary of domain
     */
    private string $domain;

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->merchantId = config('services.payment.curlec.merchantId');
        $this->merchantKey = config('services.payment.curlec.merchantKey');
        $this->employeeId = config('services.payment.curlec.employeeId');
        $this->domain = config('services.payment.curlec.domain');

    }

    /**
     * Summary of generateInstantPayment
     */
    public function generateInstantPayment(array $payload): string
    {
        $url = '/new-instant-pay';

        $payload = array_merge([
            'method' => '03',
            'merchantId' => $this->merchantId,
            'employeeId' => $this->employeeId,
            'merchantCallbackUrl' => config('app.url') . '/api/webhooks/curlec',
            'merchantUrl' => config('app.url') . '/curlec-instant-pay-response',
        ], $payload);

        $checksum = $this->generateChecksum($url, $payload);

        return $this->domain . $url . '?' . http_build_query($payload) . '&checksum=' . $checksum;
    }

    /**
     * Summary of generateCollection
     *
     * @param  array  $payload
     *                          $payload['date'] = 'd/m/Y H:i:s
     *                          $list = array
     */
    public function generateCollection(string $date, array $list)
    {
        $url = '/curlec-services';

        $payload = [
            'method' => '04',
            'merchantId' => $this->merchantId,
            'data' => '{"date":"15/11/2024 00:16:30","reminder":"false","upload":"true","list":[{"refNum":"Zhq-ccY-gDi","amount":"100.00"}]}',
        ];

        $checksum = $this->generateChecksum('', $payload);

        $payload['checksum'] = $checksum;

        $response = Http::timeout(60)
            ->withQueryParameters($payload)
            ->post('https://demo.curlec.com/curlec-services');

        info('response', ['result' => $response]);

    }
}
