<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Contact data object returned from the PracticeCS API.
 *
 * @property-read int $contactKey
 * @property-read string|null $name
 * @property-read string|null $company
 * @property-read string|null $title
 * @property-read string|null $salutation
 * @property-read string|null $url
 * @property-read string|null $fileAs
 * @property-read int $contactTypeKey
 * @property-read int $primaryPhoneTypeKey
 * @property-read int $primaryAddressTypeKey
 * @property-read int $primaryEmailTypeKey
 * @property-read int $mailingAddressTypeKey
 * @property-read int|null $portalUserKey
 * @property-read string|null $preferredLocale
 * @property-read string|null $contactGuid
 * @property-read string|null $createDateUtc
 * @property-read string|null $updateDateUtc
 * @property-read array $addresses
 * @property-read array $emails
 * @property-read array $phones
 * @property-read array $categories
 */
class Contact
{
    public function __construct(
        public readonly int $contactKey,
        public readonly ?string $name = null,
        public readonly ?string $company = null,
        public readonly ?string $title = null,
        public readonly ?string $salutation = null,
        public readonly ?string $url = null,
        public readonly ?string $fileAs = null,
        public readonly int $contactTypeKey = 1,
        public readonly int $primaryPhoneTypeKey = 1,
        public readonly int $primaryAddressTypeKey = 1,
        public readonly int $primaryEmailTypeKey = 1,
        public readonly int $mailingAddressTypeKey = 1,
        public readonly ?int $portalUserKey = null,
        public readonly ?string $preferredLocale = null,
        public readonly ?string $contactGuid = null,
        public readonly ?string $createDateUtc = null,
        public readonly ?string $updateDateUtc = null,
        public readonly array $addresses = [],
        public readonly array $emails = [],
        public readonly array $phones = [],
        public readonly array $categories = [],
    ) {}

    /**
     * Create a Contact from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            contactKey: (int) $data['contact_KEY'],
            name: $data['name'] ?? null,
            company: $data['company'] ?? null,
            title: $data['title'] ?? null,
            salutation: $data['salutation'] ?? null,
            url: $data['url'] ?? null,
            fileAs: $data['file_as'] ?? null,
            contactTypeKey: (int) ($data['contact_type_KEY'] ?? 1),
            primaryPhoneTypeKey: (int) ($data['primary__contact_phone_type_KEY'] ?? 1),
            primaryAddressTypeKey: (int) ($data['primary__contact_address_type_KEY'] ?? 1),
            primaryEmailTypeKey: (int) ($data['primary__contact_email_type_KEY'] ?? 1),
            mailingAddressTypeKey: (int) ($data['mailing__contact_address_type_KEY'] ?? 1),
            portalUserKey: isset($data['portal_user_KEY']) ? (int) $data['portal_user_KEY'] : null,
            preferredLocale: $data['preferred_locale'] ?? null,
            contactGuid: $data['contact_guid'] ?? null,
            createDateUtc: $data['create_date_utc'] ?? null,
            updateDateUtc: $data['update_date_utc'] ?? null,
            addresses: array_map(
                fn (array $a) => ContactAddress::fromArray($a),
                $data['addresses'] ?? []
            ),
            emails: array_map(
                fn (array $e) => ContactEmail::fromArray($e),
                $data['emails'] ?? []
            ),
            phones: array_map(
                fn (array $p) => ContactPhone::fromArray($p),
                $data['phones'] ?? []
            ),
            categories: $data['categories'] ?? [],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'contact_KEY' => $this->contactKey,
            'name' => $this->name,
            'company' => $this->company,
            'title' => $this->title,
            'salutation' => $this->salutation,
            'url' => $this->url,
            'file_as' => $this->fileAs,
            'contact_type_KEY' => $this->contactTypeKey,
            'primary__contact_phone_type_KEY' => $this->primaryPhoneTypeKey,
            'primary__contact_address_type_KEY' => $this->primaryAddressTypeKey,
            'primary__contact_email_type_KEY' => $this->primaryEmailTypeKey,
            'mailing__contact_address_type_KEY' => $this->mailingAddressTypeKey,
            'portal_user_KEY' => $this->portalUserKey,
            'preferred_locale' => $this->preferredLocale,
            'contact_guid' => $this->contactGuid,
            'create_date_utc' => $this->createDateUtc,
            'update_date_utc' => $this->updateDateUtc,
            'addresses' => array_map(fn (ContactAddress $a) => $a->toArray(), $this->addresses),
            'emails' => array_map(fn (ContactEmail $e) => $e->toArray(), $this->emails),
            'phones' => array_map(fn (ContactPhone $p) => $p->toArray(), $this->phones),
            'categories' => $this->categories,
        ];
    }
}
