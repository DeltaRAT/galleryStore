<?php

namespace App\Services\Payment\Requests;

use App\Services\Payment\Contracts\RequestInterface;

class IDPayRequest implements RequestInterface
{
    private $user;
    private int $amount;
    private $order_id;
    private $apiKey;

    public function __construct(array $data)
    {
        $this->amount = $data['amount'];
        $this->user = $data['user'];
        $this->order_id = $data['order_id'];
        $this->apiKey = $data['apiKey'];
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getAmount()
    {
        return $this->amount * 10;
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }
}
