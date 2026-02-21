<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Contact address data object.
 *
 * @property-read int $contactAddressKey
 * @property-read int $contactAddressTypeKey
 * @property-read string|null $address1
 * @property-read string|null $address2
 * @property-read string|null $address3
 * @property-read string|null $city
 * @property-read string|null $stateAbbreviation
 * @property-read string|null $postalCode
 * @property-read string|null $county
 * @property-read string|null $country
 * @property-read string|null $createDateUtc
 * @property-read string|null $updateDateUtc
 */
class ContactAddress
{
    public function __construct(
        public readonly int $contactAddressKey,
        public readonly int $contactAddressTypeKey,
        public readonly ?string $address1 = null,
        public readonly ?string $address2 = null,
        public readonly ?string $address3 = null,
        public readonly ?string $city = null,
        public readonly ?string $stateAbbreviation = null,
        public readonly ?string $postalCode = null,
        public readonly ?string $county = null,
        public readonly ?string $country = null,
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
            contactAddressKey: (int) $data['contact_address_KEY'],
            contactAddressTypeKey: (int) $data['contact_address_type_KEY'],
            address1: $data['address_1'] ?? null,
            address2: $data['address_2'] ?? null,
            address3: $data['address_3'] ?? null,
            city: $data['city'] ?? null,
            stateAbbreviation: $data['state_abbreviation'] ?? null,
            postalCode: $data['postal_code'] ?? null,
            county: $data['county'] ?? null,
            country: $data['country'] ?? null,
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
            'contact_address_KEY' => $this->contactAddressKey,
            'contact_address_type_KEY' => $this->contactAddressTypeKey,
            'address_1' => $this->address1,
            'address_2' => $this->address2,
            'address_3' => $this->address3,
            'city' => $this->city,
            'state_abbreviation' => $this->stateAbbreviation,
            'postal_code' => $this->postalCode,
            'county' => $this->county,
            'country' => $this->country,
            'create_date_utc' => $this->createDateUtc,
            'update_date_utc' => $this->updateDateUtc,
        ];
    }
}
