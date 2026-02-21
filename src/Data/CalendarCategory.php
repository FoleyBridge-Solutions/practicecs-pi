<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Calendar category lookup data from the PracticeCS API.
 *
 * @property-read int $calendarCategoryKey
 * @property-read string $description
 * @property-read int $color
 */
class CalendarCategory
{
    public function __construct(
        public readonly int $calendarCategoryKey,
        public readonly string $description,
        public readonly int $color = 0,
    ) {}

    /**
     * Create a CalendarCategory from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            calendarCategoryKey: (int) $data['calendar_category_KEY'],
            description: $data['description'],
            color: (int) ($data['color'] ?? 0),
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'calendar_category_KEY' => $this->calendarCategoryKey,
            'description' => $this->description,
            'color' => $this->color,
        ];
    }
}
