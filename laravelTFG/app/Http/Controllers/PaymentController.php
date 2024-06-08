<?php

namespace App\Http\Controllers;

use App\Models\MetodoPago;
use App\Services\PaypalService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paypalService;

    public function __construct(PaypalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    public function createPayment()
    {
        $apiContext = $this->paypalService->getApiContext();
        // Logic to create payment
    }

    public function executePayment(Request $request, PaypalService $paypalService)
    {
        // Logic to execute payment based on PayPal response
    }

}
