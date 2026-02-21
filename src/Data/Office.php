<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Office data object returned from the PracticeCS API.
 *
 * @property-read int $officeKey
 * @property-read string $officeId
 * @property-read string $description
 * @property-read int $contactKey
 * @property-read string|null $createDateUtc
 * @property-read string|null $updateDateUtc
 */
class Office
{
    public function __construct(
        public readonly int $officeKey,
        public readonly string $officeId,
        public readonly string $description,
        public readonly int $contactKey,
        public readonly ?string $createDateUtc = null,
        public readonly ?string $updateDateUtc = null,
    ) {}

    /**
     * Create an Office from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            officeKey: (int) $data['office_KEY'],
            officeId: $data['office_id'],
            description: $data['description'],
            contactKey: (int) $data['contact_KEY'],
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
            'office_KEY' => $this->officeKey,
            'office_id' => $this->officeId,
            'description' => $this->description,
            'contact_KEY' => $this->contactKey,
            'create_date_utc' => $this->createDateUtc,
            'update_date_utc' => $this->updateDateUtc,
        ];
    }
}
