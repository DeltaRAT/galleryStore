<?php

namespace App\Services\Payment;

use App\Services\Payment\Contracts\RequestInterface;
use App\Services\Payment\Exceptions\ProviderNotFoundException;
use App\Services\Payment\Requests\IDPayRequest;

class paymentService
{
    public const IDPAY = 'IDPayProvider';
    public const ZARINPAL = 'ZarinpalProvider';

    public function __construct(private string $providerName,
                                  private RequestInterface $request )
    {
    }

    public function pay()
    {
        return $this->findProvider()->pay();
    }

    private function findProvider()
    {
        $className = 'App\\Services\\Payment\\Provider' . $this->providerName;

        if (class_exists($className))
        {
            throw new ProviderNotFoundException(' درگاه پرداخت انتخاب شده پیدا نشد');
        }

        return new $className($this->request);
    }
}

$idPayRequest = new IDPayRequest();
$paymentService = new paymentService(paymentService::IDPAY, $idPayRequest);
$paymentService->pay();
