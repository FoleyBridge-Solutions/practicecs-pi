<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Contact phone data object.
 *
 * @property-read int $contactPhoneKey
 * @property-read int $contactPhoneTypeKey
 * @property-read string|null $phone
 * @property-read string|null $extension
 * @property-read string|null $createDateUtc
 * @property-read string|null $updateDateUtc
 */
class ContactPhone
{
    public function __construct(
        public readonly int $contactPhoneKey,
        public readonly int $contactPhoneTypeKey,
        public readonly ?string $phone = null,
        public readonly ?string $extension = null,
        public readonly ?string $createDateUtc = null,
        public readonly ?string $updateDateUtc = null,
    ) {}

    /**
     * Create from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            contactPhoneKey: (int) $data['contact_phone_KEY'],
            contactPhoneTypeKey: (int) $data['contact_phone_type_KEY'],
            phone: $data['phone'] ?? null,
            extension: $data['extension'] ?? null,
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
            'contact_phone_KEY' => $this->contactPhoneKey,
            'contact_phone_type_KEY' => $this->contactPhoneTypeKey,
            'phone' => $this->phone,
            'extension' => $this->extension,
            'create_date_utc' => $this->createDateUtc,
            'update_date_utc' => $this->updateDateUtc,
        ];
    }
}
