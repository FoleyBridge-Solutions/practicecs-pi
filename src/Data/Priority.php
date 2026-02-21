<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Priority lookup data from the PracticeCS API.
 *
 * @property-read int $priorityKey
 * @property-read int $sort
 * @property-read string $description
 */
class Priority
{
    public function __construct(
        public readonly int $priorityKey,
        public readonly string $description,
        public readonly int $sort = 0,
    ) {}

    /**
     * Create a Priority from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            priorityKey: (int) $data['priority_KEY'],
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
            'priority_KEY' => $this->priorityKey,
            'sort' => $this->sort,
            'description' => $this->description,
        ];
    }
}
