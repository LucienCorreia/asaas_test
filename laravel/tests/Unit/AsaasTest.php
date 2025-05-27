<?php

use App\Integrations\Asaas\AsaasImplementation;
use App\Integrations\Asaas\Responses\CustomerResponse;
use App\Integrations\Asaas\Requests\ConsumerRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use App\Integrations\Asaas\Requests\PaymentRequest;
use App\Integrations\Asaas\Requests\PaymentCreditCardRequest;
use App\Integrations\Asaas\Responses\PaymentResponse;
use App\Integrations\Asaas\Enums\StatusEnum;
use App\Integrations\Asaas\Enums\BillingTypeEnum;
use App\Integrations\Asaas\Requests\CreditCardRequest;
use App\Integrations\Asaas\Requests\CreditCardHolderInfoRequest;
use Carbon\Carbon;

test('create a customer successfully', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'id' => 'cus_123456',
            'name' => 'João Silva',
            'cpfCnpj' => '12345678909',
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $client = new Client(['handler' => $handlerStack]);

    $asaas = new AsaasImplementation($client);

    $request = new ConsumerRequest(
        name: 'João Silva',
        cpfCnpj: '12345678909',
    );

    $response = $asaas->createCustomer($request);

    expect($response)
        ->id->toBe('cus_123456')
        ->name->toBe('João Silva')
        ->cpfCnpj->toBe('12345678909');
});

test('create a customer error response', function () {
    $mock = new MockHandler([
        new Response(400, [], json_encode([
            'errors' => [
                [
                    'description' => 'CPF/CNPJ inválido',
                ]
            ]
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $client = new Client(['handler' => $handlerStack]);

    $asaas = new AsaasImplementation($client);

    $request = new ConsumerRequest(
        name: 'João Silva',
        cpfCnpj: '12345678909',
    );

    $asaas->createCustomer($request);
})->throws(ClientException::class);

test('create a boleto payment successfully', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'id' => 'pay_123456',
            'customer' => 'cus_123456',
            'status' => 'PENDING',
            'bankSlipUrl' => 'https://boleto.asaas.com.br/123456',
            'value' => '100',
            'dueDate' => '2023-01-01',
            'billingType' => 'BOLETO',
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $client = new Client(['handler' => $handlerStack]);

    $asaas = new AsaasImplementation($client);

    $response = $asaas->createPayment(new \App\Integrations\Asaas\Requests\PaymentRequest(
        'cus_123456',
        BillingTypeEnum::BOLETO,
        '100',
        Carbon::now()->addDays(30)->format('Y-m-d'),
    ));

    expect($response)
        ->id->toBe('pay_123456')
        ->status->toBe(StatusEnum::PENDING)
        ->bankSlipUrl->toBe('https://boleto.asaas.com.br/123456')
        ->value->toBe('100')
        ->dueDate->toBe('2023-01-01')
        ->billingType->toBe(BillingTypeEnum::BOLETO);
});

test('create a boleto payment error response', function () {
    $mock = new MockHandler([
        new Response(400, [], json_encode([
            'errors' => [
                [
                    'description' => 'Cliente não encontrado',
                ]
            ]
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $client = new Client(['handler' => $handlerStack]);

    $asaas = new AsaasImplementation($client);

    $asaas->createPayment(new \App\Integrations\Asaas\Requests\PaymentRequest(
        'cus_123456',
        BillingTypeEnum::BOLETO,
        '100',
        Carbon::now()->addDays(30)->format('Y-m-d'),
    ));
})->throws(ClientException::class);

test('create a pix payment successfully', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'id' => 'pay_123456',
            'customer' => 'cus_123456',
            'status' => 'PENDING',
            'bankSlipUrl' => 'https://boleto.asaas.com.br/123456',
            'value' => '100',
            'dueDate' => '2023-01-01',
            'billingType' => 'PIX',
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $client = new Client(['handler' => $handlerStack]);

    $asaas = new AsaasImplementation($client);

    $response = $asaas->createPayment(new \App\Integrations\Asaas\Requests\PaymentRequest(
        'cus_123456',
        BillingTypeEnum::PIX,
        '100',
        Carbon::now()->addDays(30)->format('Y-m-d'),
    ));

    expect($response)
        ->id->toBe('pay_123456')
        ->status->toBe(StatusEnum::PENDING)
        ->bankSlipUrl->toBe('https://boleto.asaas.com.br/123456')
        ->value->toBe('100')
        ->dueDate->toBe('2023-01-01')
        ->billingType->toBe(BillingTypeEnum::PIX);
});

test('create a pix payment error response', function () {
    $mock = new MockHandler([
        new Response(400, [], json_encode([
            'errors' => [
                [
                    'description' => 'Cliente não encontrado',
                ]
            ]
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $client = new Client(['handler' => $handlerStack]);

    $asaas = new AsaasImplementation($client);

    $asaas->createPayment(new \App\Integrations\Asaas\Requests\PaymentRequest(
        'cus_123456',
        BillingTypeEnum::PIX,
        '100',
        Carbon::now()->addDays(30)->format('Y-m-d'),
    ));
})->throws(ClientException::class);

test('create a pix qrcode successfully', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'payload' => 'payload',
            'encodedImage' => 'encodedImage',
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $client = new Client(['handler' => $handlerStack]);

    $asaas = new AsaasImplementation($client);

    $response = $asaas->getPix('pay_123456');

    expect($response)
        ->payload->toBe('payload')
        ->encodedImage->toBe('encodedImage');
});

test('create a pix qrcode error response', function () {
    $mock = new MockHandler([
        new Response(400, [], json_encode([
            'errors' => [
                [
                    'description' => 'Pagamento não encontrado',
                ]
            ]
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $client = new Client(['handler' => $handlerStack]);

    $asaas = new AsaasImplementation($client);

    $asaas->getPix('pay_123456');
})->throws(ClientException::class);

test('create a credit card payment successfully', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'id' => 'pay_123456',
            'customer' => 'cus_123456',
            'status' => 'CONFIRMED',
            'bankSlipUrl' => 'https://boleto.asaas.com.br/123456',
            'value' => '100',
            'dueDate' => '2023-01-01',
            'billingType' => 'CREDIT_CARD',
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $client = new Client(['handler' => $handlerStack]);

    $asaas = new AsaasImplementation($client);

    $response = $asaas->createPaymentCreditCard(
        new PaymentCreditCardRequest(
            'cus_123456',
            '100',
            Carbon::now()->addDays(30)->format('Y-m-d'),
            new CreditCardRequest(
                'John Doe',
                '1234567890123456',
                '12',
                '2029',
                '123',
            ),
            new CreditCardHolderInfoRequest(
                'John Doe',
                'ZVw0F@example.com',
                '12345678901',
                '1234567890',
                '123456789',
                '1234567890123456',
            ),
        ),
    );

    expect($response)
        ->id->toBe('pay_123456')
        ->status->toBe(StatusEnum::CONFIRMED)
        ->value->toBe('100')
        ->dueDate->toBe('2023-01-01')
        ->billingType->toBe(BillingTypeEnum::CREDIT_CARD);
});

test('create a credit card payment error response', function () {
        $mock = new MockHandler([
        new Response(400, [], json_encode([
            'errors' => [
                [
                    'description' => 'Cartão expirado',
                ]
            ]
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $client = new Client(['handler' => $handlerStack]);

    $asaas = new AsaasImplementation($client);

    $asaas->createPaymentCreditCard(
        new PaymentCreditCardRequest(
            'cus_123456',
            '100',
            Carbon::now()->addDays(30)->format('Y-m-d'),
            new CreditCardRequest(
                'John Doe',
                '1234567890123456',
                '12',
                '2010',
                '123',
            ),
            new CreditCardHolderInfoRequest(
                'John Doe',
                'ZVw0F@example.com',
                '12345678901',
                '1234567890',
                '123456789',
                '1234567890123456',
            ),
        ),
    );
})->throws(ClientException::class);
