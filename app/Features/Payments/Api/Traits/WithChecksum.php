<?php

namespace App\Features\Payments\Api\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait WithChecksum
{
    /**
     * Summary of generateChecksum
     *
     * @param  string  $params
     */
    public function generateChecksum(string $url, array $payload): string
    {
        if ($url !== '') {
            $url = "{$url}?";
        }

        $encrypted = "{$this->merchantKey}|{$url}{$this->convertPayloadToQueryParamsWithoutEncode($payload)}";

        return hash('sha256', $encrypted);
    }

    /**
     * Summary of validateInstantPaymentChecksum
     */
    public function validateInstantPaymentChecksum(array $payload, string $checksum): bool
    {
        try {
            $result = hash(
                'sha256',
                "{$this->merchantKey}|" . $payload['fpx_fpxTxnId'] . '|' . $payload['fpx_sellerExOrderNo'] . '|' . $payload['fpx_fpxTxnTime'] . '|' .
                $payload['fpx_sellerOrderNo'] . '|' . $payload['fpx_sellerId'] . '|' . $payload['fpx_txnCurrency'] . '|' . $payload['fpx_txnAmount'] . '|' .
                $payload['fpx_buyerName'] . '|' . $payload['fpx_buyerBankId'] . '|' . $payload['fpx_debitAuthCode'] . '|' . $payload['fpx_type']
            );

            return $result === $checksum;

        } catch (Exception $e) {
            Log::error('Error creating curlec checksum', ['message' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Summary of convertPayloadToQueryParamsWithoutEncode
     */
    public function convertPayloadToQueryParamsWithoutEncode(array $params): string
    {
        return implode('&', array_map(
            fn ($key, $value): string => "{$key}={$value}",
            array_keys($params),
            $params
        ));
    }
}
