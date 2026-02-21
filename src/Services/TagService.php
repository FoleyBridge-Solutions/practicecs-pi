<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Tag;
use FoleyBridgeSolutions\PracticeCsPI\Data\TaggedItem;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Tag operations against the PracticeCS API.
 *
 * Full CRUD for tags, tagged items, and item tag lookups.
 */
class TagService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new tag service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Tags
    // -----------------------------------------------------------------

    /**
     * List tags with pagination.
     *
     * @param  int  $limit  Maximum results to return
     * @param  int  $offset  Number of results to skip
     * @return array{data: Tag[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function list(int $limit = 50, int $offset = 0): array
    {
        $response = $this->api->get('/api/tags', [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return [
            'data' => array_map(
                fn (array $item) => Tag::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single tag by its primary key.
     *
     * @param  int  $tagKey  The tag's primary key
     * @return Tag|null Tag DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function find(int $tagKey): ?Tag
    {
        try {
            $response = $this->api->get("/api/tags/{$tagKey}");

            if (empty($response['data'])) {
                return null;
            }

            return Tag::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new tag.
     *
     * @param  array  $data  Tag data
     * @return Tag The created tag
     *
     * @throws PracticeCsException
     */
    public function create(array $data): Tag
    {
        $response = $this->api->post('/api/tags', $data);

        return Tag::fromArray($response['data']);
    }

    /**
     * Update an existing tag.
     *
     * @param  int  $tagKey  The tag's primary key
     * @param  array  $data  Fields to update
     * @return Tag The updated tag
     *
     * @throws PracticeCsException
     */
    public function update(int $tagKey, array $data): Tag
    {
        $response = $this->api->put("/api/tags/{$tagKey}", $data);

        return Tag::fromArray($response['data']);
    }

    /**
     * Delete a tag.
     *
     * @param  int  $tagKey  The tag's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function delete(int $tagKey): bool
    {
        $response = $this->api->delete("/api/tags/{$tagKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Tagged Items
    // -----------------------------------------------------------------

    /**
     * List items associated with a tag.
     *
     * @param  int  $tagKey  The tag's primary key
     * @param  int  $limit  Maximum results to return
     * @param  int  $offset  Number of results to skip
     * @return array{data: TaggedItem[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function taggedItems(int $tagKey, int $limit = 50, int $offset = 0): array
    {
        $response = $this->api->get("/api/tags/{$tagKey}/items", [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return [
            'data' => array_map(
                fn (array $item) => TaggedItem::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Add an item to a tag.
     *
     * @param  int  $tagKey  The tag's primary key
     * @param  array  $data  Tagged item data
     * @return TaggedItem The created tagged item
     *
     * @throws PracticeCsException
     */
    public function addTaggedItem(int $tagKey, array $data): TaggedItem
    {
        $response = $this->api->post("/api/tags/{$tagKey}/items", $data);

        return TaggedItem::fromArray($response['data']);
    }

    /**
     * Remove an item from a tag.
     *
     * @param  int  $tagKey  The tag's primary key
     * @param  int  $itemKey  The tagged item's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function removeTaggedItem(int $tagKey, int $itemKey): bool
    {
        $response = $this->api->delete("/api/tags/{$tagKey}/items/{$itemKey}");

        return $response['data']['deleted'] ?? false;
    }

    /**
     * List tagged items across all tags, optionally filtered.
     *
     * @param  array  $filters  Associative array of query filters
     * @return array[] Array of tagged item arrays
     *
     * @throws PracticeCsException
     */
    public function itemTags(array $filters = []): array
    {
        $response = $this->api->get('/api/tagged-items', $filters);

        return $response['data'] ?? [];
    }
}
