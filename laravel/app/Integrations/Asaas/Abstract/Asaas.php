<?php

namespace App\Integrations\Asaas\Abstract;

use App\Integrations\Asaas\Requests\ConsumerRequest;
use App\Integrations\Asaas\Responses\CustomerResponse;
use App\Integrations\Asaas\Requests\PaymentCreditCardRequest;
use App\Integrations\Asaas\Responses\PaymentResponse;
use App\Integrations\Asaas\Requests\PaymentRequest;
use App\Integrations\Asaas\Responses\PixResponse;

interface Asaas
{
    public function createCustomer(ConsumerRequest $consumerRequest): CustomerResponse;

    public function createPaymentCreditCard(PaymentCreditCardRequest $paymentCreditCardRequest): PaymentResponse;

    public function createPayment(PaymentRequest $paymentRequest): PaymentResponse;

    public function getPix(string $payment): PixResponse;
}
