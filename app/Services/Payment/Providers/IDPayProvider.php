<?php

namespace App\Services\Payment\Provider;

use App\services\payment\contracts\PayableInterface;
use App\services\payment\contracts\VerifiableInterface;
use App\Sevices\Payment\Contracts\AbstractProviderInterface;

class IDPayProvider extends AbstractProviderInterface implements PayableInterface, VerifiableInterface
{
    public function pay()
    {
        // TODO: Implement pay() method.
    }

    public function verify()
    {
        // TODO: Implement verify() method.
    }
}
