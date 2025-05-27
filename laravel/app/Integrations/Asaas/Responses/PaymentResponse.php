<?php

namespace App\Integrations\Asaas\Responses;

use App\Integrations\Asaas\Enums\StatusEnum;
use App\Integrations\Asaas\Enums\BillingTypeEnum;

readonly class PaymentResponse
{
    public function __construct(
        public string $id,
        public StatusEnum $status,
        public ?string $bankSlipUrl,
        public string $customer,
        public BillingTypeEnum $billingType,
        public string $value,
        public string $dueDate,
    ) {
    }

    public static function fromArray(\StdClass $data): self
    {
        return new self(
            $data->id,
            StatusEnum::from($data->status),
            $data->bankSlipUrl ?? null,
            $data->customer,
            BillingTypeEnum::from($data->billingType),
            $data->value,
            $data->dueDate,
        );
    }
}
