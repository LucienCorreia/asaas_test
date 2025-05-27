<?php

namespace App\Integrations\Asaas\Responses;

readonly class CustomerResponse
{
    public function __construct(
        public string $name,
        public string $cpfCnpj,
        public string $id,
    ) {
    }

    public static function fromArray(\StdClass $data): self
    {
        return new self(
            $data->name,
            $data->cpfCnpj,
            $data->id,
        );
    }
}
