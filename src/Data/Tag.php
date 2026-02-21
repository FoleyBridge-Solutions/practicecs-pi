<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Tag data object returned from the PracticeCS API.
 *
 * @property-read int $tagKey
 * @property-read string $description
 */
class Tag
{
    public function __construct(
        public readonly int $tagKey,
        public readonly string $description,
    ) {}

    /**
     * Create a Tag from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            tagKey: (int) $data['tag_KEY'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'tag_KEY' => $this->tagKey,
            'description' => $this->description,
        ];
    }
}
