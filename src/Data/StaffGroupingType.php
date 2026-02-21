<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Staff grouping type lookup data from the PracticeCS API.
 *
 * @property-read int $staffGroupingTypeKey
 * @property-read int $sort
 * @property-read string $description
 * @property-read ?string $tableName
 * @property-read ?string $columnName
 */
class StaffGroupingType
{
    public function __construct(
        public readonly int $staffGroupingTypeKey,
        public readonly string $description,
        public readonly int $sort = 0,
        public readonly ?string $tableName = null,
        public readonly ?string $columnName = null,
    ) {}

    /**
     * Create a StaffGroupingType from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            staffGroupingTypeKey: (int) $data['staff_grouping_type_KEY'],
            description: $data['description'],
            sort: (int) ($data['sort'] ?? 0),
            tableName: $data['table_name'] ?? null,
            columnName: $data['column_name'] ?? null,
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'staff_grouping_type_KEY' => $this->staffGroupingTypeKey,
            'sort' => $this->sort,
            'description' => $this->description,
            'table_name' => $this->tableName,
            'column_name' => $this->columnName,
        ];
    }
}
