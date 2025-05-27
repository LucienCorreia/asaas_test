<?php

namespace App\Integrations\Asaas\Requests;

use App\Integrations\Asaas\Enums\BillingTypeEnum;

readonly class PaymentRequest
{
    public function __construct(
        public string $customer,
        public BillingTypeEnum $billingType,
        public string $value,
        public string $dueDate
    ) {
    }

    public function toArray(): array
    {
        return [
            'customer' => $this->customer,
            'billingType' => $this->billingType->value,
            'value' => $this->value,
            'dueDate' => $this->dueDate,
        ];
    }
}
