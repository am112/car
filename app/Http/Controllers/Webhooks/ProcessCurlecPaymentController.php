<?php

namespace App\Http\Controllers\Webhooks;

use App\Features\Payments\Facades\PaymentGateway;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Log;

class ProcessCurlecPaymentController extends Controller
{
    /**
     * Summary of __invoke
     */
    public function __invoke(Request $request): Response|ResponseFactory
    {
        $data = $request->validate([
            'reference_no' => 'required',
            'amount' => 'required',
            'payment_reference' => 'required',
            'paid_at' => 'required',
        ]);

        // money conversion 1.00 => 100
        $data['amount'] = (float) $data['amount'] * 100;

        try {
            PaymentGateway::driver('curlec')->process($data);

            return response([
                'message' => 'OK',
            ], 200);

        } catch (Exception $e) {

            Log::channel('payments')->error('process curlec payment', [$e->__toString()]);

            return response([
                'message' => $e->getMessage(),
            ], 400);

        }
    }
}
