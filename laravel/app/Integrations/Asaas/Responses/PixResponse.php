<?php

namespace App\Integrations\Asaas\Responses;

readonly class PixResponse
{
    public function __construct(
        public string $payload,
        public string $encodedImage,
    )
    {}

    public static function fromArray(\StdClass $data): self
    {
        return new self(
            $data->payload,
            $data->encodedImage,
        );
    }
}
