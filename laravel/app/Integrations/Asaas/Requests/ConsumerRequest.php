<?php

namespace App\Integrations\Asaas\Requests;

readonly class ConsumerRequest
{
    public function __construct(
        public string $name,
        public string $cpfCnpj,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'cpfCnpj' => $this->cpfCnpj,
        ];
    }
}
