<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Event class type lookup data from the PracticeCS API.
 *
 * @property-read int $eventClassTypeKey
 * @property-read string $description
 */
class EventClassType
{
    public function __construct(
        public readonly int $eventClassTypeKey,
        public readonly string $description,
    ) {}

    /**
     * Create an EventClassType from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            eventClassTypeKey: (int) $data['event_class_type_KEY'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'event_class_type_KEY' => $this->eventClassTypeKey,
            'description' => $this->description,
        ];
    }
}
