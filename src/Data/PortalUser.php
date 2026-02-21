<?php

// src/Data/PortalUser.php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Portal user data object returned from the PracticeCS API.
 *
 * @property-read int $portalUserKey
 * @property-read string|null $login
 * @property-read string $lastModificationDate
 * @property-read string|null $endDate
 * @property-read string|null $firstName
 * @property-read string|null $lastName
 * @property-read string|null $emailAddress
 * @property-read bool|null $onlineBillPayEnabled
 * @property-read bool|null $mobileCsEnabled
 * @property-read int $portalUserTypeKey
 * @property-read string|null $registrationDate
 * @property-read string|null $registrationSentDate
 * @property-read string|null $expirationDate
 * @property-read int $firmId
 */
class PortalUser
{
    public function __construct(
        public readonly int $portalUserKey,
        public readonly ?string $login = null,
        public readonly string $lastModificationDate = '',
        public readonly ?string $endDate = null,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $emailAddress = null,
        public readonly ?bool $onlineBillPayEnabled = null,
        public readonly ?bool $mobileCsEnabled = null,
        public readonly int $portalUserTypeKey = 0,
        public readonly ?string $registrationDate = null,
        public readonly ?string $registrationSentDate = null,
        public readonly ?string $expirationDate = null,
        public readonly int $firmId = 0,
    ) {}

    /**
     * Create a PortalUser from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            portalUserKey: (int) $data['portal_user_KEY'],
            login: $data['login'] ?? null,
            lastModificationDate: (string) ($data['last_modification_date'] ?? ''),
            endDate: $data['end_date'] ?? null,
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            emailAddress: $data['email_address'] ?? null,
            onlineBillPayEnabled: isset($data['online_bill_pay_enabled']) ? (bool) $data['online_bill_pay_enabled'] : null,
            mobileCsEnabled: isset($data['mobilecs_enabled']) ? (bool) $data['mobilecs_enabled'] : null,
            portalUserTypeKey: (int) ($data['portal_user_type_KEY'] ?? 0),
            registrationDate: $data['registration_date'] ?? null,
            registrationSentDate: $data['registration_sent_date'] ?? null,
            expirationDate: $data['expiration_date'] ?? null,
            firmId: (int) ($data['firm_id'] ?? 0),
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'portal_user_KEY' => $this->portalUserKey,
            'login' => $this->login,
            'last_modification_date' => $this->lastModificationDate,
            'end_date' => $this->endDate,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email_address' => $this->emailAddress,
            'online_bill_pay_enabled' => $this->onlineBillPayEnabled,
            'mobilecs_enabled' => $this->mobileCsEnabled,
            'portal_user_type_KEY' => $this->portalUserTypeKey,
            'registration_date' => $this->registrationDate,
            'registration_sent_date' => $this->registrationSentDate,
            'expiration_date' => $this->expirationDate,
            'firm_id' => $this->firmId,
        ];
    }
}
