<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Office;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Office operations against the PracticeCS API.
 *
 * Full CRUD for offices.
 */
class OfficeService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new office service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Offices
    // -----------------------------------------------------------------

    /**
     * List offices.
     *
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: Office[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function list(int $limit = 50, int $offset = 0): array
    {
        $query = ['limit' => $limit, 'offset' => $offset];

        $response = $this->api->get('/api/offices', $query);

        return [
            'data' => array_map(
                fn (array $item) => Office::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single office.
     *
     * @param  int  $officeKey  The office's primary key
     * @return Office|null Office DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function find(int $officeKey): ?Office
    {
        try {
            $response = $this->api->get("/api/offices/{$officeKey}");

            if (empty($response['data'])) {
                return null;
            }

            return Office::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new office.
     *
     * @param  array  $data  Office data
     * @return Office The created office
     *
     * @throws PracticeCsException
     */
    public function create(array $data): Office
    {
        $response = $this->api->post('/api/offices', $data);

        return Office::fromArray($response['data']);
    }

    /**
     * Update an office.
     *
     * @param  int  $officeKey  The office's primary key
     * @param  array  $data  Fields to update
     * @return Office The updated office
     *
     * @throws PracticeCsException
     */
    public function update(int $officeKey, array $data): Office
    {
        $response = $this->api->put("/api/offices/{$officeKey}", $data);

        return Office::fromArray($response['data']);
    }

    /**
     * Delete an office.
     *
     * @param  int  $officeKey  The office's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function delete(int $officeKey): bool
    {
        $response = $this->api->delete("/api/offices/{$officeKey}");

        return $response['data']['deleted'] ?? false;
    }
}
