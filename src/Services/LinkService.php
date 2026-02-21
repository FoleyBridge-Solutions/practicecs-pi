<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Link;
use FoleyBridgeSolutions\PracticeCsPI\Data\LinkGroup;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Link operations against the PracticeCS API.
 *
 * Full CRUD for link groups and links.
 */
class LinkService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new link service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Link Groups
    // -----------------------------------------------------------------

    /**
     * List link groups with pagination.
     *
     * @param  int  $limit  Maximum results to return
     * @param  int  $offset  Number of results to skip
     * @return array{data: LinkGroup[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function listGroups(int $limit = 50, int $offset = 0): array
    {
        $response = $this->api->get('/api/link-groups', [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return [
            'data' => array_map(
                fn (array $item) => LinkGroup::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single link group by its primary key.
     *
     * @param  int  $groupKey  The link group's primary key
     * @return LinkGroup|null LinkGroup DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function findGroup(int $groupKey): ?LinkGroup
    {
        try {
            $response = $this->api->get("/api/link-groups/{$groupKey}");

            if (empty($response['data'])) {
                return null;
            }

            return LinkGroup::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new link group.
     *
     * @param  array  $data  Link group data
     * @return LinkGroup The created link group
     *
     * @throws PracticeCsException
     */
    public function createGroup(array $data): LinkGroup
    {
        $response = $this->api->post('/api/link-groups', $data);

        return LinkGroup::fromArray($response['data']);
    }

    /**
     * Update an existing link group.
     *
     * @param  int  $groupKey  The link group's primary key
     * @param  array  $data  Fields to update
     * @return LinkGroup The updated link group
     *
     * @throws PracticeCsException
     */
    public function updateGroup(int $groupKey, array $data): LinkGroup
    {
        $response = $this->api->put("/api/link-groups/{$groupKey}", $data);

        return LinkGroup::fromArray($response['data']);
    }

    /**
     * Delete a link group.
     *
     * @param  int  $groupKey  The link group's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deleteGroup(int $groupKey): bool
    {
        $response = $this->api->delete("/api/link-groups/{$groupKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Links
    // -----------------------------------------------------------------

    /**
     * List links with optional filters and pagination.
     *
     * @param  array  $filters  Associative array of query filters
     * @param  int  $limit  Maximum results to return
     * @param  int  $offset  Number of results to skip
     * @return array{data: Link[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function list(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $query = array_merge($filters, [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        $response = $this->api->get('/api/links', $query);

        return [
            'data' => array_map(
                fn (array $item) => Link::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single link by its primary key.
     *
     * @param  int  $linkKey  The link's primary key
     * @return Link|null Link DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function find(int $linkKey): ?Link
    {
        try {
            $response = $this->api->get("/api/links/{$linkKey}");

            if (empty($response['data'])) {
                return null;
            }

            return Link::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new link.
     *
     * @param  array  $data  Link data
     * @return Link The created link
     *
     * @throws PracticeCsException
     */
    public function create(array $data): Link
    {
        $response = $this->api->post('/api/links', $data);

        return Link::fromArray($response['data']);
    }

    /**
     * Update an existing link.
     *
     * @param  int  $linkKey  The link's primary key
     * @param  array  $data  Fields to update
     * @return Link The updated link
     *
     * @throws PracticeCsException
     */
    public function update(int $linkKey, array $data): Link
    {
        $response = $this->api->put("/api/links/{$linkKey}", $data);

        return Link::fromArray($response['data']);
    }

    /**
     * Delete a link.
     *
     * @param  int  $linkKey  The link's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function delete(int $linkKey): bool
    {
        $response = $this->api->delete("/api/links/{$linkKey}");

        return $response['data']['deleted'] ?? false;
    }
}
