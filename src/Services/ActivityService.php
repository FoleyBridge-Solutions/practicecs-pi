<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Activity;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Activity operations against the PracticeCS API.
 *
 * Full CRUD for activities and activity categories.
 */
class ActivityService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new activity service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Activities
    // -----------------------------------------------------------------

    /**
     * List or search activities.
     *
     * @param  string|null  $search  Search term
     * @param  int|null  $categoryKey  Filter by category
     * @param  int|null  $statusKey  Filter by status
     * @param  int|null  $classKey  Filter by class
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: Activity[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function list(
        ?string $search = null,
        ?int $categoryKey = null,
        ?int $statusKey = null,
        ?int $classKey = null,
        int $limit = 50,
        int $offset = 0
    ): array {
        $query = ['limit' => $limit, 'offset' => $offset];

        if ($search !== null) {
            $query['search'] = $search;
        }
        if ($categoryKey !== null) {
            $query['activity_category_KEY'] = $categoryKey;
        }
        if ($statusKey !== null) {
            $query['activity_status_KEY'] = $statusKey;
        }
        if ($classKey !== null) {
            $query['activity_class_KEY'] = $classKey;
        }

        $response = $this->api->get('/api/activities', $query);

        return [
            'data' => array_map(
                fn (array $item) => Activity::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single activity.
     *
     * @param  int  $activityKey  The activity's primary key
     * @return Activity|null Activity DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function find(int $activityKey): ?Activity
    {
        try {
            $response = $this->api->get("/api/activities/{$activityKey}");

            if (empty($response['data'])) {
                return null;
            }

            return Activity::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Find an activity by activity_id.
     *
     * @param  string  $activityId  The activity ID
     * @return Activity|null Activity DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function findByActivityId(string $activityId): ?Activity
    {
        try {
            $response = $this->api->get("/api/activities/by-id/{$activityId}");

            if (empty($response['data'])) {
                return null;
            }

            return Activity::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new activity.
     *
     * @param  array  $data  Activity data
     * @return Activity The created activity
     *
     * @throws PracticeCsException
     */
    public function create(array $data): Activity
    {
        $response = $this->api->post('/api/activities', $data);

        return Activity::fromArray($response['data']);
    }

    /**
     * Update an activity.
     *
     * @param  int  $activityKey  The activity's primary key
     * @param  array  $data  Fields to update
     * @return Activity The updated activity
     *
     * @throws PracticeCsException
     */
    public function update(int $activityKey, array $data): Activity
    {
        $response = $this->api->put("/api/activities/{$activityKey}", $data);

        return Activity::fromArray($response['data']);
    }

    /**
     * Delete an activity.
     *
     * @param  int  $activityKey  The activity's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function delete(int $activityKey): bool
    {
        $response = $this->api->delete("/api/activities/{$activityKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Activity Categories
    // -----------------------------------------------------------------

    /**
     * List all activity categories.
     *
     * @return array[] Array of category arrays
     *
     * @throws PracticeCsException
     */
    public function listCategories(): array
    {
        $response = $this->api->get('/api/activity-categories');

        return $response['data'] ?? [];
    }

    /**
     * Get a single activity category.
     *
     * @param  int  $categoryKey  The category's primary key
     * @return array|null Category data or null
     *
     * @throws PracticeCsException
     */
    public function findCategory(int $categoryKey): ?array
    {
        try {
            $response = $this->api->get("/api/activity-categories/{$categoryKey}");

            return $response['data'] ?? null;
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create an activity category.
     *
     * @param  array  $data  Category data
     * @return array The created category
     *
     * @throws PracticeCsException
     */
    public function createCategory(array $data): array
    {
        $response = $this->api->post('/api/activity-categories', $data);

        return $response['data'] ?? [];
    }

    /**
     * Update an activity category.
     *
     * @param  int  $categoryKey  The category's primary key
     * @param  array  $data  Fields to update
     * @return array The updated category
     *
     * @throws PracticeCsException
     */
    public function updateCategory(int $categoryKey, array $data): array
    {
        $response = $this->api->put("/api/activity-categories/{$categoryKey}", $data);

        return $response['data'] ?? [];
    }

    /**
     * Delete an activity category.
     *
     * @param  int  $categoryKey  The category's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deleteCategory(int $categoryKey): bool
    {
        $response = $this->api->delete("/api/activity-categories/{$categoryKey}");

        return $response['data']['deleted'] ?? false;
    }
}
