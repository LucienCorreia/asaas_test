<?php

namespace App\Integrations\Asaas;

use GuzzleHttp\Client;
use App\Integrations\Asaas\Abstract\Asaas;
use App\Integrations\Asaas\Requests\ConsumerRequest;
use App\Integrations\Asaas\Requests\PaymentCreditCardRequest;
use App\Integrations\Asaas\Requests\PaymentRequest;
use App\Integrations\Asaas\Responses\CustomerResponse;
use App\Integrations\Asaas\Responses\PaymentResponse;
use App\Integrations\Asaas\Responses\PixResponse;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class AsaasImplementation implements Asaas
{
    public function __construct(
        private Client $client
    )
    {}

    public function createCustomer(ConsumerRequest $consumerRequest): CustomerResponse
    {
        try {
            $response = $this->client->post('/v3/customers', [
                    'json' => $consumerRequest->toArray(),
                ])->getBody()->getContents();

            return CustomerResponse::fromArray(
                json_decode($response)
            );
        } catch (RequestException $e) {
            throw  $e;
        } catch (ClientException $e) {
            throw  $e;
        }
    }

    public function createPaymentCreditCard(PaymentCreditCardRequest $paymentCreditCardRequest): PaymentResponse
    {
        try {
            $response = $this->client->post('/v3/payments', [
                    'json' => $paymentCreditCardRequest->toArray(),
                ])->getBody()->getContents();

            return PaymentResponse::fromArray(
                json_decode($response)
            );
        } catch (RequestException $e) {
            throw  $e;
        } catch (ClientException $e) {
            throw  $e;
        }
    }

    public function createPayment(PaymentRequest $paymentRequest): PaymentResponse
    {
        try {
            $response = $this->client->post('/v3/payments', [
                    'json' => $paymentRequest->toArray(),
                ])->getBody()->getContents();

            return PaymentResponse::fromArray(
                json_decode($response)
            );
        } catch (RequestException $e) {
            throw  $e;
        } catch (ClientException $e) {
            throw  $e;
        }
    }

    public function getPix(string $payment): PixResponse
    {
        try {
            return PixResponse::fromArray(
                json_decode($this->client->get('/v3/payments/' . $payment . '/pixQrCode')->getBody()->getContents())
            );
        } catch (RequestException $e) {
            throw  $e;
        }
    }
}
