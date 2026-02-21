<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Client status lookup data from the PracticeCS API.
 *
 * @property-read int $clientStatusKey
 * @property-read string $description
 */
class ClientStatus
{
    public function __construct(
        public readonly int $clientStatusKey,
        public readonly string $description,
    ) {}

    /**
     * Create a ClientStatus from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            clientStatusKey: (int) $data['client_status_KEY'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'client_status_KEY' => $this->clientStatusKey,
            'description' => $this->description,
        ];
    }
}
