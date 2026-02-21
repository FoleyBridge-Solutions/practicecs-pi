<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Staff data object returned from the PracticeCS API.
 *
 * @property-read int $staffKey
 * @property-read string $staffId
 * @property-read string $description
 * @property-read string|null $userName
 * @property-read string|null $firstName
 * @property-read string|null $middleName
 * @property-read string|null $lastName
 * @property-read string|null $dateHired
 * @property-read string|null $dateLeft
 * @property-read int $staffStatusKey
 * @property-read int|null $staffLevelKey
 * @property-read int|null $officeKey
 * @property-read int|null $departmentKey
 * @property-read int|null $supervisorStaffKey
 * @property-read int $contactKey
 * @property-read string|null $createDateUtc
 * @property-read string|null $updateDateUtc
 * @property-read array|null $level
 * @property-read array|null $department
 * @property-read array $billingRates
 * @property-read array $targetRanges
 */
class Staff
{
    public function __construct(
        public readonly int $staffKey,
        public readonly string $staffId,
        public readonly string $description,
        public readonly ?string $userName = null,
        public readonly ?string $firstName = null,
        public readonly ?string $middleName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $dateHired = null,
        public readonly ?string $dateLeft = null,
        public readonly int $staffStatusKey = 1,
        public readonly ?int $staffLevelKey = null,
        public readonly ?int $officeKey = null,
        public readonly ?int $departmentKey = null,
        public readonly ?int $supervisorStaffKey = null,
        public readonly int $contactKey = 0,
        public readonly ?string $createDateUtc = null,
        public readonly ?string $updateDateUtc = null,
        public readonly ?array $level = null,
        public readonly ?array $department = null,
        public readonly array $billingRates = [],
        public readonly array $targetRanges = [],
    ) {}

    /**
     * Create a Staff from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            staffKey: (int) $data['staff_KEY'],
            staffId: $data['staff_id'],
            description: $data['description'],
            userName: $data['user_name'] ?? null,
            firstName: $data['first_name'] ?? null,
            middleName: $data['middle_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            dateHired: $data['date_hired'] ?? null,
            dateLeft: $data['date_left'] ?? null,
            staffStatusKey: (int) ($data['staff_status_KEY'] ?? 1),
            staffLevelKey: isset($data['staff_level_KEY']) ? (int) $data['staff_level_KEY'] : null,
            officeKey: isset($data['office_KEY']) ? (int) $data['office_KEY'] : null,
            departmentKey: isset($data['department_KEY']) ? (int) $data['department_KEY'] : null,
            supervisorStaffKey: isset($data['supervisor__staff_KEY']) ? (int) $data['supervisor__staff_KEY'] : null,
            contactKey: (int) ($data['contact_KEY'] ?? 0),
            createDateUtc: $data['create_date_utc'] ?? null,
            updateDateUtc: $data['update_date_utc'] ?? null,
            level: $data['level'] ?? null,
            department: $data['department'] ?? null,
            billingRates: $data['billing_rates'] ?? [],
            targetRanges: $data['target_ranges'] ?? [],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'staff_KEY' => $this->staffKey,
            'staff_id' => $this->staffId,
            'description' => $this->description,
            'user_name' => $this->userName,
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,
            'date_hired' => $this->dateHired,
            'date_left' => $this->dateLeft,
            'staff_status_KEY' => $this->staffStatusKey,
            'staff_level_KEY' => $this->staffLevelKey,
            'office_KEY' => $this->officeKey,
            'department_KEY' => $this->departmentKey,
            'supervisor__staff_KEY' => $this->supervisorStaffKey,
            'contact_KEY' => $this->contactKey,
            'create_date_utc' => $this->createDateUtc,
            'update_date_utc' => $this->updateDateUtc,
            'level' => $this->level,
            'department' => $this->department,
            'billing_rates' => $this->billingRates,
            'target_ranges' => $this->targetRanges,
        ];
    }
}
