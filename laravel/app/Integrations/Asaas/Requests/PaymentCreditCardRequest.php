<?php

namespace App\Integrations\Asaas\Requests;

use App\Integrations\Asaas\Enums\BillingTypeEnum;

readonly class PaymentCreditCardRequest extends PaymentRequest
{
    public function __construct(
        string $customer,
        string $value,
        string $dueDate,
        public CreditCardRequest $creditCard,
        public CreditCardHolderInfoRequest $creditCardHolderInfo
    ) {
        parent::__construct($customer, BillingTypeEnum::CREDIT_CARD, $value, $dueDate);
    }

    public function toArray(): array
    {
        return [
            'customer' => $this->customer,
            'billingType' => $this->billingType->value,
            'value' => $this->value,
            'dueDate' => $this->dueDate,
            'creditCard' => $this->creditCard->toArray(),
            'creditCardHolderInfo' => $this->creditCardHolderInfo->toArray(),
        ];
    }
}
