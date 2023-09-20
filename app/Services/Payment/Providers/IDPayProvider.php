<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Contracts\AbstractProviderInterface;
use App\services\payment\contracts\PayableInterface;
use App\services\payment\contracts\VerifiableInterface;


class IDPayProvider extends AbstractProviderInterface implements PayableInterface, VerifiableInterface
{
    public function pay()
    {

        $params = array(
            'order_id' => $this->request->getOrderId(),
            'amount' => $this->request->getAmount(),
            'name' => $this->request->getUser()->name,
            'phone' => $this->request->getUser()->mobile,
            'mail' => $this->request->getUser()->email,
            'callback' => route('payment.callback'),
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY:' . $this->request->getApiKey() . '',
            'X-SANDBOX: 1'  // باعث این می شود که فرایند پرداخت ما آزمایشی باشد و تست ها رو بتونیم انجام بدیم، در صورت لانچ حذف شود.
        ));

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);

        if (isset($result['error_code'])){
            throw new \InvalidArgumentException($result['error_message']);
        }

        return redirect()->away();
    }

    public function verify()
    {
        // TODO: Implement verify() method.
    }
}
