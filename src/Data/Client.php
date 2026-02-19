<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Client data object returned from the PracticeCS API.
 *
 * @property-read int $clientKey
 * @property-read string $clientId
 * @property-read string $clientName
 * @property-read string|null $firstName
 * @property-read string|null $lastName
 * @property-read string|null $federalTin
 */
class Client
{
    public function __construct(
        public readonly int $clientKey,
        public readonly string $clientId,
        public readonly string $clientName,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $federalTin = null,
    ) {}

    /**
     * Create a Client from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            clientKey: (int) $data['client_KEY'],
            clientId: $data['client_id'],
            clientName: $data['client_name'],
            firstName: $data['individual_first_name'] ?? null,
            lastName: $data['individual_last_name'] ?? null,
            federalTin: $data['federal_tin'] ?? null,
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'client_KEY' => $this->clientKey,
            'client_id' => $this->clientId,
            'client_name' => $this->clientName,
            'individual_first_name' => $this->firstName,
            'individual_last_name' => $this->lastName,
            'federal_tin' => $this->federalTin,
        ];
    }
}
