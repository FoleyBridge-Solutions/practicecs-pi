<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Custom field operations against the PracticeCS API.
 *
 * Provides access to custom fields, groupings, values, and table information.
 */
class CustomFieldService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new custom field service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Custom Fields
    // -----------------------------------------------------------------

    /**
     * List or search custom fields.
     *
     * @param  array  $filters  Optional filters to apply
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: array[], meta: array}
     *
     * @throws PracticeCsException
     */
    public function list(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $query = array_merge($filters, ['limit' => $limit, 'offset' => $offset]);

        $response = $this->api->get('/api/custom-fields', $query);

        return [
            'data' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single custom field.
     *
     * @param  int  $customFieldKey  The custom field's primary key
     * @return array|null Custom field data or null if not found
     *
     * @throws PracticeCsException
     */
    public function find(int $customFieldKey): ?array
    {
        try {
            $response = $this->api->get("/api/custom-fields/{$customFieldKey}");

            return $response['data'] ?? null;
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Get groupings for a custom field.
     *
     * @param  int  $customFieldKey  The custom field's primary key
     * @return array[] Array of grouping data
     *
     * @throws PracticeCsException
     */
    public function groupings(int $customFieldKey): array
    {
        $response = $this->api->get("/api/custom-fields/{$customFieldKey}/groupings");

        return $response['data'] ?? [];
    }

    // -----------------------------------------------------------------
    // Custom Values
    // -----------------------------------------------------------------

    /**
     * List custom field values.
     *
     * @param  array  $filters  Optional filters to apply
     * @return array{data: array[], meta: array}
     *
     * @throws PracticeCsException
     */
    public function listValues(array $filters = []): array
    {
        $response = $this->api->get('/api/custom-values', $filters);

        return [
            'data' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Create or update a custom field value.
     *
     * @param  array  $data  Custom value data
     * @return array The created or updated custom value
     *
     * @throws PracticeCsException
     */
    public function upsertValue(array $data): array
    {
        $response = $this->api->put('/api/custom-values', $data);

        return $response['data'] ?? [];
    }

    /**
     * Delete a custom field value.
     *
     * @param  int  $customValueKey  The custom value's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deleteValue(int $customValueKey): bool
    {
        $response = $this->api->delete("/api/custom-values/{$customValueKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Table Information
    // -----------------------------------------------------------------

    /**
     * Get table information for custom fields.
     *
     * @return array[] Array of table information
     *
     * @throws PracticeCsException
     */
    public function tableInformation(): array
    {
        $response = $this->api->get('/api/table-information');

        return $response['data'] ?? [];
    }
}
