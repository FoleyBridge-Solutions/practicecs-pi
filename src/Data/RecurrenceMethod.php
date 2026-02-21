<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Recurrence method lookup data from the PracticeCS API.
 *
 * @property-read int $recurrenceMethodKey
 * @property-read int $sort
 * @property-read string $description
 */
class RecurrenceMethod
{
    public function __construct(
        public readonly int $recurrenceMethodKey,
        public readonly string $description,
        public readonly int $sort = 0,
    ) {}

    /**
     * Create a RecurrenceMethod from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            recurrenceMethodKey: (int) $data['recurrence_method_KEY'],
            description: $data['description'],
            sort: (int) ($data['sort'] ?? 0),
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'recurrence_method_KEY' => $this->recurrenceMethodKey,
            'sort' => $this->sort,
            'description' => $this->description,
        ];
    }
}
