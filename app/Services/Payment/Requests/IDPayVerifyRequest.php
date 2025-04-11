<?php
namespace App\Services\Payment\Requests;

use App\Services\Payment\Contracts\RequestInterface;

class IDPayVerifyRequest implements RequestInterface
{
    private $order_id;
    private $apiKey;
    private $id;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->order_id = $data['order_id'];
        $this->apiKey = $data['apiKey'];
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }
}
