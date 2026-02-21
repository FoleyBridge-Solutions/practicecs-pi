<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Project;
use FoleyBridgeSolutions\PracticeCsPI\Data\ScheduleEntry;
use FoleyBridgeSolutions\PracticeCsPI\Data\ScheduleItem;
use FoleyBridgeSolutions\PracticeCsPI\Data\Task;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Project & scheduling operations against the PracticeCS API.
 *
 * Full CRUD for projects, tasks, schedule items, schedule entries,
 * and schedule item assignments.
 */
class ProjectService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new project service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Projects
    // -----------------------------------------------------------------

    /**
     * List or search projects.
     *
     * @param  array  $filters  Associative array of filters
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: Project[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function list(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $query = array_merge($filters, ['limit' => $limit, 'offset' => $offset]);

        $response = $this->api->get('/api/projects', $query);

        return [
            'data' => array_map(
                fn (array $item) => Project::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single project with tasks and extensions.
     *
     * @param  int  $projectKey  The project's primary key
     * @return Project|null Project DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function find(int $projectKey): ?Project
    {
        try {
            $response = $this->api->get("/api/projects/{$projectKey}");

            if (empty($response['data'])) {
                return null;
            }

            return Project::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new project.
     *
     * @param  array  $data  Project data
     * @return Project The created project
     *
     * @throws PracticeCsException
     */
    public function create(array $data): Project
    {
        $response = $this->api->post('/api/projects', $data);

        return Project::fromArray($response['data']);
    }

    /**
     * Update a project.
     *
     * @param  int  $projectKey  The project's primary key
     * @param  array  $data  Fields to update
     * @return Project The updated project
     *
     * @throws PracticeCsException
     */
    public function update(int $projectKey, array $data): Project
    {
        $response = $this->api->put("/api/projects/{$projectKey}", $data);

        return Project::fromArray($response['data']);
    }

    /**
     * Delete a project (and its tasks/extensions).
     *
     * @param  int  $projectKey  The project's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function delete(int $projectKey): bool
    {
        $response = $this->api->delete("/api/projects/{$projectKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Tasks
    // -----------------------------------------------------------------

    /**
     * List tasks for a project.
     *
     * @param  int  $projectKey  The project's primary key
     * @return Task[] Array of Task DTOs
     *
     * @throws PracticeCsException
     */
    public function listTasks(int $projectKey): array
    {
        $response = $this->api->get("/api/projects/{$projectKey}/tasks");

        return array_map(
            fn (array $item) => Task::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get a single task.
     *
     * @param  int  $taskKey  The task's primary key
     * @return Task|null Task DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function findTask(int $taskKey): ?Task
    {
        try {
            $response = $this->api->get("/api/tasks/{$taskKey}");

            if (empty($response['data'])) {
                return null;
            }

            return Task::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new task.
     *
     * @param  array  $data  Task data
     * @return Task The created task
     *
     * @throws PracticeCsException
     */
    public function createTask(array $data): Task
    {
        $response = $this->api->post('/api/tasks', $data);

        return Task::fromArray($response['data']);
    }

    /**
     * Update a task.
     *
     * @param  int  $taskKey  The task's primary key
     * @param  array  $data  Fields to update
     * @return Task The updated task
     *
     * @throws PracticeCsException
     */
    public function updateTask(int $taskKey, array $data): Task
    {
        $response = $this->api->put("/api/tasks/{$taskKey}", $data);

        return Task::fromArray($response['data']);
    }

    /**
     * Delete a task.
     *
     * @param  int  $taskKey  The task's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deleteTask(int $taskKey): bool
    {
        $response = $this->api->delete("/api/tasks/{$taskKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Schedule Items
    // -----------------------------------------------------------------

    /**
     * List or search schedule items.
     *
     * @param  array  $filters  Associative array of filters
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: ScheduleItem[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function listScheduleItems(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $query = array_merge($filters, ['limit' => $limit, 'offset' => $offset]);

        $response = $this->api->get('/api/schedule-items', $query);

        return [
            'data' => array_map(
                fn (array $item) => ScheduleItem::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single schedule item with assignments.
     *
     * @param  int  $itemKey  The schedule item's primary key
     * @return ScheduleItem|null ScheduleItem DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function findScheduleItem(int $itemKey): ?ScheduleItem
    {
        try {
            $response = $this->api->get("/api/schedule-items/{$itemKey}");

            if (empty($response['data'])) {
                return null;
            }

            return ScheduleItem::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new schedule item.
     *
     * @param  array  $data  Schedule item data
     * @return ScheduleItem The created schedule item
     *
     * @throws PracticeCsException
     */
    public function createScheduleItem(array $data): ScheduleItem
    {
        $response = $this->api->post('/api/schedule-items', $data);

        return ScheduleItem::fromArray($response['data']);
    }

    /**
     * Update a schedule item.
     *
     * @param  int  $itemKey  The schedule item's primary key
     * @param  array  $data  Fields to update
     * @return ScheduleItem The updated schedule item
     *
     * @throws PracticeCsException
     */
    public function updateScheduleItem(int $itemKey, array $data): ScheduleItem
    {
        $response = $this->api->put("/api/schedule-items/{$itemKey}", $data);

        return ScheduleItem::fromArray($response['data']);
    }

    /**
     * Delete a schedule item (and its assignments/entries).
     *
     * @param  int  $itemKey  The schedule item's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deleteScheduleItem(int $itemKey): bool
    {
        $response = $this->api->delete("/api/schedule-items/{$itemKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Schedule Item Assignments
    // -----------------------------------------------------------------

    /**
     * List assignments for a schedule item.
     *
     * @param  int  $itemKey  The schedule item's primary key
     * @return array[] Array of assignment arrays
     *
     * @throws PracticeCsException
     */
    public function listAssignments(int $itemKey): array
    {
        $response = $this->api->get("/api/schedule-items/{$itemKey}/assignments");

        return $response['data'] ?? [];
    }

    /**
     * Add an assignment to a schedule item.
     *
     * @param  int  $itemKey  The schedule item's primary key
     * @param  array  $data  Assignment data
     * @return array The created assignment
     *
     * @throws PracticeCsException
     */
    public function createAssignment(int $itemKey, array $data): array
    {
        $response = $this->api->post("/api/schedule-items/{$itemKey}/assignments", $data);

        return $response['data'] ?? [];
    }

    /**
     * Delete an assignment.
     *
     * @param  int  $itemKey  The schedule item's primary key
     * @param  int  $staffKey  The staff member's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deleteAssignment(int $itemKey, int $staffKey): bool
    {
        $response = $this->api->delete("/api/schedule-items/{$itemKey}/assignments/{$staffKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Schedule Entries
    // -----------------------------------------------------------------

    /**
     * List schedule entries, optionally filtered.
     *
     * @param  array  $filters  Associative array of filters
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: ScheduleEntry[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function listScheduleEntries(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $query = array_merge($filters, ['limit' => $limit, 'offset' => $offset]);

        $response = $this->api->get('/api/schedule-entries', $query);

        return [
            'data' => array_map(
                fn (array $item) => ScheduleEntry::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single schedule entry.
     *
     * @param  int  $entryKey  The schedule entry's primary key
     * @return ScheduleEntry|null ScheduleEntry DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function findScheduleEntry(int $entryKey): ?ScheduleEntry
    {
        try {
            $response = $this->api->get("/api/schedule-entries/{$entryKey}");

            if (empty($response['data'])) {
                return null;
            }

            return ScheduleEntry::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new schedule entry.
     *
     * @param  array  $data  Schedule entry data
     * @return ScheduleEntry The created schedule entry
     *
     * @throws PracticeCsException
     */
    public function createScheduleEntry(array $data): ScheduleEntry
    {
        $response = $this->api->post('/api/schedule-entries', $data);

        return ScheduleEntry::fromArray($response['data']);
    }

    /**
     * Update a schedule entry.
     *
     * @param  int  $entryKey  The schedule entry's primary key
     * @param  array  $data  Fields to update
     * @return ScheduleEntry The updated schedule entry
     *
     * @throws PracticeCsException
     */
    public function updateScheduleEntry(int $entryKey, array $data): ScheduleEntry
    {
        $response = $this->api->put("/api/schedule-entries/{$entryKey}", $data);

        return ScheduleEntry::fromArray($response['data']);
    }

    /**
     * Delete a schedule entry.
     *
     * @param  int  $entryKey  The schedule entry's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deleteScheduleEntry(int $entryKey): bool
    {
        $response = $this->api->delete("/api/schedule-entries/{$entryKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Project Sub-Resources
    // -----------------------------------------------------------------

    /**
     * List sheet entries for a project.
     *
     * @param  int  $projectKey  The project's primary key
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: array[], meta: array}
     *
     * @throws PracticeCsException
     */
    public function projectSheetEntries(int $projectKey, int $limit = 50, int $offset = 0): array
    {
        $response = $this->api->get("/api/projects/{$projectKey}/sheet-entries", [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return [
            'data' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ];
    }
}
