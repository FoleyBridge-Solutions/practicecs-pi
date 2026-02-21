<?php

declare(strict_types=1);

// src/Data/InteractionSubtype.php

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Interaction subtype lookup data object returned from the PracticeCS API.
 *
 * @property-read int $interactionSubtypeKey
 * @property-read int $interactionTypeKey
 * @property-read string $description
 */
class InteractionSubtype
{
    public function __construct(
        public readonly int $interactionSubtypeKey,
        public readonly int $interactionTypeKey,
        public readonly string $description,
    ) {}

    /**
     * Create an InteractionSubtype from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            interactionSubtypeKey: (int) $data['interaction_subtype_KEY'],
            interactionTypeKey: (int) $data['interaction_type_KEY'],
            description: (string) $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'interaction_subtype_KEY' => $this->interactionSubtypeKey,
            'interaction_type_KEY' => $this->interactionTypeKey,
            'description' => $this->description,
        ];
    }
}
