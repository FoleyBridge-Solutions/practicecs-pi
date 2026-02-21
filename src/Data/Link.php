<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Link data object returned from the PracticeCS API.
 *
 * @property-read int $linkKey
 * @property-read int $linkGroupKey
 * @property-read string $description
 * @property-read int $sort
 * @property-read int $linkTypeKey
 * @property-read string $target
 * @property-read string|null $comment
 * @property-read string|null $createMachineName
 * @property-read int $createStaffKey
 * @property-read int $updateStaffKey
 * @property-read int $createChangesetKey
 * @property-read int $updateChangesetKey
 */
class Link
{
    public function __construct(
        public readonly int $linkKey,
        public readonly int $linkGroupKey,
        public readonly string $description,
        public readonly int $sort = 0,
        public readonly int $linkTypeKey = 0,
        public readonly string $target = '',
        public readonly ?string $comment = null,
        public readonly ?string $createMachineName = null,
        public readonly int $createStaffKey = 0,
        public readonly int $updateStaffKey = 0,
        public readonly int $createChangesetKey = 0,
        public readonly int $updateChangesetKey = 0,
    ) {}

    /**
     * Create a Link from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            linkKey: (int) $data['link_KEY'],
            linkGroupKey: (int) $data['link_group_KEY'],
            description: $data['description'],
            sort: (int) ($data['sort'] ?? 0),
            linkTypeKey: (int) $data['link_type_KEY'],
            target: $data['target'],
            comment: $data['comment'] ?? null,
            createMachineName: $data['create_machine_name'] ?? null,
            createStaffKey: (int) ($data['create__staff_KEY'] ?? 0),
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
            'link_KEY' => $this->linkKey,
            'link_group_KEY' => $this->linkGroupKey,
            'description' => $this->description,
            'sort' => $this->sort,
            'link_type_KEY' => $this->linkTypeKey,
            'target' => $this->target,
            'comment' => $this->comment,
            'create_machine_name' => $this->createMachineName,
            'create__staff_KEY' => $this->createStaffKey,
            'update__staff_KEY' => $this->updateStaffKey,
            'create__changeset_KEY' => $this->createChangesetKey,
            'update__changeset_KEY' => $this->updateChangesetKey,
        ];
    }
}
