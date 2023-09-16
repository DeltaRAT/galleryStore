<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Payment\paymentService;
use App\Services\Payment\Requests\IDPayRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay()
    {
        $user = User::find();
        $idPayRequest = new IDPayRequest([
            'amount' => 1000,
            'user' => $user
        ]);
        $paymentService = new paymentService(paymentService::IDPAY, $idPayRequest);
        $paymentService->pay();

    }
}
