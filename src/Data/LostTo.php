<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Lost-to lookup data from the PracticeCS API.
 *
 * @property-read int $lostToKey
 * @property-read string $description
 */
class LostTo
{
    public function __construct(
        public readonly int $lostToKey,
        public readonly string $description,
    ) {}

    /**
     * Create a LostTo from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            lostToKey: (int) $data['lost_to_KEY'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'lost_to_KEY' => $this->lostToKey,
            'description' => $this->description,
        ];
    }
}
