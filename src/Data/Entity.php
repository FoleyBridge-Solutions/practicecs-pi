<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Entity data object returned from the PracticeCS API.
 *
 * @property-read int $entityKey
 * @property-read string $entityId
 * @property-read string $description
 * @property-read string|null $createDateUtc
 * @property-read string|null $updateDateUtc
 */
class Entity
{
    public function __construct(
        public readonly int $entityKey,
        public readonly string $entityId,
        public readonly string $description,
        public readonly ?string $createDateUtc = null,
        public readonly ?string $updateDateUtc = null,
    ) {}

    /**
     * Create an Entity from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            entityKey: (int) $data['entity_KEY'],
            entityId: $data['entity_id'],
            description: $data['description'],
            createDateUtc: $data['create_date_utc'] ?? null,
            updateDateUtc: $data['update_date_utc'] ?? null,
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'entity_KEY' => $this->entityKey,
            'entity_id' => $this->entityId,
            'description' => $this->description,
            'create_date_utc' => $this->createDateUtc,
            'update_date_utc' => $this->updateDateUtc,
        ];
    }
}
