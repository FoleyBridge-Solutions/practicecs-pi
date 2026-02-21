<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Entity;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Entity operations against the PracticeCS API.
 *
 * Full CRUD for entities, plus entity client listing.
 */
class EntityService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new entity service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Entities
    // -----------------------------------------------------------------

    /**
     * List entities.
     *
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: Entity[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function list(int $limit = 50, int $offset = 0): array
    {
        $query = ['limit' => $limit, 'offset' => $offset];

        $response = $this->api->get('/api/entities', $query);

        return [
            'data' => array_map(
                fn (array $item) => Entity::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single entity.
     *
     * @param  int  $entityKey  The entity's primary key
     * @return Entity|null Entity DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function find(int $entityKey): ?Entity
    {
        try {
            $response = $this->api->get("/api/entities/{$entityKey}");

            if (empty($response['data'])) {
                return null;
            }

            return Entity::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new entity.
     *
     * @param  array  $data  Entity data
     * @return Entity The created entity
     *
     * @throws PracticeCsException
     */
    public function create(array $data): Entity
    {
        $response = $this->api->post('/api/entities', $data);

        return Entity::fromArray($response['data']);
    }

    /**
     * Update an entity.
     *
     * @param  int  $entityKey  The entity's primary key
     * @param  array  $data  Fields to update
     * @return Entity The updated entity
     *
     * @throws PracticeCsException
     */
    public function update(int $entityKey, array $data): Entity
    {
        $response = $this->api->put("/api/entities/{$entityKey}", $data);

        return Entity::fromArray($response['data']);
    }

    /**
     * Delete an entity.
     *
     * @param  int  $entityKey  The entity's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function delete(int $entityKey): bool
    {
        $response = $this->api->delete("/api/entities/{$entityKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Entity Clients
    // -----------------------------------------------------------------

    /**
     * List clients belonging to an entity.
     *
     * @param  int  $entityKey  The entity's primary key
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: array[], meta: array}
     *
     * @throws PracticeCsException
     */
    public function clients(int $entityKey, int $limit = 50, int $offset = 0): array
    {
        $response = $this->api->get("/api/entities/{$entityKey}/clients", [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return [
            'data' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ];
    }
}
