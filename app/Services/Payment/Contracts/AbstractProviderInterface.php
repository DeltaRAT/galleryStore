<?php

namespace App\Sevices\Payment\Contracts;

use App\Services\Payment\Contracts\RequestInterface;

abstract class AbstractProviderInterface
{
    public function __construct(RequestInterface $request)
    {

    }
}
