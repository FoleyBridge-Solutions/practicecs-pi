<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * LinkGroup data object returned from the PracticeCS API.
 *
 * @property-read int $linkGroupKey
 * @property-read int $tableInformationKey
 * @property-read int $rowKey
 * @property-read string $description
 * @property-read int $sort
 * @property-read int $updateStaffKey
 * @property-read int $createChangesetKey
 * @property-read int $updateChangesetKey
 */
class LinkGroup
{
    public function __construct(
        public readonly int $linkGroupKey,
        public readonly int $tableInformationKey,
        public readonly int $rowKey,
        public readonly string $description,
        public readonly int $sort = 0,
        public readonly int $updateStaffKey = 0,
        public readonly int $createChangesetKey = 0,
        public readonly int $updateChangesetKey = 0,
    ) {}

    /**
     * Create a LinkGroup from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            linkGroupKey: (int) $data['link_group_KEY'],
            tableInformationKey: (int) $data['table_information_KEY'],
            rowKey: (int) $data['row_KEY'],
            description: $data['description'],
            sort: (int) ($data['sort'] ?? 0),
            updateStaffKey: (int) ($data['update__staff_KEY'] ?? 0),
            createChangesetKey: (int) ($data['create__changeset_KEY'] ?? 0),
            updateChangesetKey: (int) ($data['update__changeset_KEY'] ?? 0),
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'link_group_KEY' => $this->linkGroupKey,
            'table_information_KEY' => $this->tableInformationKey,
            'row_KEY' => $this->rowKey,
            'description' => $this->description,
            'sort' => $this->sort,
            'update__staff_KEY' => $this->updateStaffKey,
            'create__changeset_KEY' => $this->createChangesetKey,
            'update__changeset_KEY' => $this->updateChangesetKey,
        ];
    }
}
