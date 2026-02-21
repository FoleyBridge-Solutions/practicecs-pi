<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Won reason lookup data from the PracticeCS API.
 *
 * @property-read int $wonReasonKey
 * @property-read string $description
 */
class WonReason
{
    public function __construct(
        public readonly int $wonReasonKey,
        public readonly string $description,
    ) {}

    /**
     * Create a WonReason from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            wonReasonKey: (int) $data['won_reason_KEY'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'won_reason_KEY' => $this->wonReasonKey,
            'description' => $this->description,
        ];
    }
}
