<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Tagged item data object returned from the PracticeCS API.
 *
 * @property-read int $rowKey
 * @property-read int $tableInformationKey
 * @property-read int $tagKey
 * @property-read int $taggedItemSort
 * @property-read string|null $tableName
 * @property-read string|null $tagDescription
 */
class TaggedItem
{
    public function __construct(
        public readonly int $rowKey,
        public readonly int $tableInformationKey,
        public readonly int $tagKey,
        public readonly int $taggedItemSort = 0,
        public readonly ?string $tableName = null,
        public readonly ?string $tagDescription = null,
    ) {}

    /**
     * Create a TaggedItem from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            rowKey: (int) $data['row_KEY'],
            tableInformationKey: (int) $data['table_information_KEY'],
            tagKey: (int) $data['tag_KEY'],
            taggedItemSort: (int) ($data['tagged_item_sort'] ?? 0),
            tableName: $data['table_name'] ?? null,
            tagDescription: $data['tag_description'] ?? null,
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'row_KEY' => $this->rowKey,
            'table_information_KEY' => $this->tableInformationKey,
            'tag_KEY' => $this->tagKey,
            'tagged_item_sort' => $this->taggedItemSort,
            'table_name' => $this->tableName,
            'tag_description' => $this->tagDescription,
        ];
    }
}
