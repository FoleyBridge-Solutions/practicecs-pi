<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Client balance data object returned from the PracticeCS API.
 *
 * @property-read string $clientId
 * @property-read string $clientName
 * @property-read float $balance
 */
class Balance
{
    public function __construct(
        public readonly string $clientId,
        public readonly string $clientName,
        public readonly float $balance,
    ) {}

    /**
     * Create a Balance from an API response array.
     *
     * @param array $data API response data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            clientId: $data['client_id'],
            clientName: $data['client_name'],
            balance: (float) $data['balance'],
        );
    }

    /**
     * Convert to array representation.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'client_id' => $this->clientId,
            'client_name' => $this->clientName,
            'balance' => $this->balance,
        ];
    }
}
