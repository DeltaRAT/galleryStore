<?php

namespace App\Services\Payment\Requests;

use App\Services\Payment\Contracts\RequestInterface;

class IDPayRequest implements RequestInterface
{
    private string $user;
    private int $amount;

    public function __construct(array $data)
    {
        $this->amount = $data['amount'];
        $this->user = $data['user'];
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}
