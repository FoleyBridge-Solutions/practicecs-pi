<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Lost reason lookup data from the PracticeCS API.
 *
 * @property-read int $lostReasonKey
 * @property-read string $description
 */
class LostReason
{
    public function __construct(
        public readonly int $lostReasonKey,
        public readonly string $description,
    ) {}

    /**
     * Create a LostReason from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            lostReasonKey: (int) $data['lost_reason_KEY'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'lost_reason_KEY' => $this->lostReasonKey,
            'description' => $this->description,
        ];
    }
}
