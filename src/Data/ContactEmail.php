<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Contact email data object.
 *
 * @property-read int $contactEmailKey
 * @property-read int $contactEmailTypeKey
 * @property-read string|null $email
 * @property-read string|null $displayAs
 * @property-read string|null $createDateUtc
 * @property-read string|null $updateDateUtc
 */
class ContactEmail
{
    public function __construct(
        public readonly int $contactEmailKey,
        public readonly int $contactEmailTypeKey,
        public readonly ?string $email = null,
        public readonly ?string $displayAs = null,
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
            contactEmailKey: (int) $data['contact_email_KEY'],
            contactEmailTypeKey: (int) $data['contact_email_type_KEY'],
            email: $data['email'] ?? null,
            displayAs: $data['display_as'] ?? null,
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
            'contact_email_KEY' => $this->contactEmailKey,
            'contact_email_type_KEY' => $this->contactEmailTypeKey,
            'email' => $this->email,
            'display_as' => $this->displayAs,
            'create_date_utc' => $this->createDateUtc,
            'update_date_utc' => $this->updateDateUtc,
        ];
    }
}
