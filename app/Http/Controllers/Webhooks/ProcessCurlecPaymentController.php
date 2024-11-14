<?php

namespace App\Http\Controllers\Webhooks;

use App\Features\Payments\Facades\PaymentGateway;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

class ProcessCurlecPaymentController extends Controller
{
    /**
     * Summary of __invoke
     */
    public function __invoke(Request $request): Response|ResponseFactory
    {
        PaymentGateway::driver('curlec')->process($request->all());

        return response()->json([
            'message' => 'OK',
        ], 200);
    }
}
