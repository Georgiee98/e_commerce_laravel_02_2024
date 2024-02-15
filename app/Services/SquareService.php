<?php


namespace App\Services;

use Square\Exceptions\ApiException;
use Square\SquareClient;
use Square\Environment;
use Square\Models\CreatePaymentRequest;
use Square\Models\Money;


class SquareService
{
    protected $client;

    public function __construct()
    {
        $this->client = new SquareClient([
            'accessToken' => env('SQUARE_SANDBOX_ACCESS_TOKEN'),
            'environment' => Environment::SANDBOX,
        ]);
    }

    public function createPayment($amount, $nonce)
    {
        $money = new Money();
        $money->setAmount($amount);
        $money->setCurrency("USD");

        $payment = new CreatePaymentRequest($nonce, uniqid(), $money);

        try {
            $result = $this->client->getPaymentsApi()->createPayment($payment);
            return $result->getResult();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    // public function createPayment($amount, $sourceId, $idempotencyKey)
    // {
    //     $amountMoney = new \Square\Models\Money();
    //     $amountMoney->setAmount($amount);
    //     $amountMoney->setCurrency('USD');

    //     $payment = new CreatePaymentRequest($sourceId, $idempotencyKey, $amountMoney);

    //     try {
    //         $result = $this->client->getPaymentsApi()->createPayment($payment);
    //         return $result->getResult();
    //     } catch (ApiException $e) {
    //         // Consider logging the error or handling it as per your application's requirements
    //         return null;
    //     }
    // }


}