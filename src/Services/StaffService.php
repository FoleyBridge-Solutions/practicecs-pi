<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Staff;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Staff operations against the PracticeCS API.
 *
 * Full CRUD for staff, departments, levels, billing rates, and target ranges.
 */
class StaffService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new staff service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Staff
    // -----------------------------------------------------------------

    /**
     * List or search staff.
     *
     * @param  string|null  $search  Search term
     * @param  int|null  $staffStatusKey  Filter by status
     * @param  int|null  $departmentKey  Filter by department
     * @param  int|null  $officeKey  Filter by office
     * @param  int|null  $staffLevelKey  Filter by level
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: Staff[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function list(
        ?string $search = null,
        ?int $staffStatusKey = null,
        ?int $departmentKey = null,
        ?int $officeKey = null,
        ?int $staffLevelKey = null,
        int $limit = 50,
        int $offset = 0
    ): array {
        $query = ['limit' => $limit, 'offset' => $offset];

        if ($search !== null) {
            $query['search'] = $search;
        }
        if ($staffStatusKey !== null) {
            $query['staff_status_KEY'] = $staffStatusKey;
        }
        if ($departmentKey !== null) {
            $query['department_KEY'] = $departmentKey;
        }
        if ($officeKey !== null) {
            $query['office_KEY'] = $officeKey;
        }
        if ($staffLevelKey !== null) {
            $query['staff_level_KEY'] = $staffLevelKey;
        }

        $response = $this->api->get('/api/staff', $query);

        return [
            'data' => array_map(
                fn (array $item) => Staff::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single staff member with related data.
     *
     * @param  int  $staffKey  The staff member's primary key
     * @return Staff|null Staff DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function find(int $staffKey): ?Staff
    {
        try {
            $response = $this->api->get("/api/staff/{$staffKey}");

            if (empty($response['data'])) {
                return null;
            }

            return Staff::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Find a staff member by staff_id.
     *
     * @param  string  $staffId  The staff ID
     * @return Staff|null Staff DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function findByStaffId(string $staffId): ?Staff
    {
        try {
            $response = $this->api->get("/api/staff/by-id/{$staffId}");

            if (empty($response['data'])) {
                return null;
            }

            return Staff::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new staff member.
     *
     * @param  array  $data  Staff data
     * @return Staff The created staff
     *
     * @throws PracticeCsException
     */
    public function create(array $data): Staff
    {
        $response = $this->api->post('/api/staff', $data);

        return Staff::fromArray($response['data']);
    }

    /**
     * Update a staff member.
     *
     * @param  int  $staffKey  The staff member's primary key
     * @param  array  $data  Fields to update
     * @return Staff The updated staff
     *
     * @throws PracticeCsException
     */
    public function update(int $staffKey, array $data): Staff
    {
        $response = $this->api->put("/api/staff/{$staffKey}", $data);

        return Staff::fromArray($response['data']);
    }

    /**
     * Delete a staff member.
     *
     * @param  int  $staffKey  The staff member's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function delete(int $staffKey): bool
    {
        $response = $this->api->delete("/api/staff/{$staffKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Departments
    // -----------------------------------------------------------------

    /**
     * List all departments.
     *
     * @return array[] Array of department arrays
     *
     * @throws PracticeCsException
     */
    public function listDepartments(): array
    {
        $response = $this->api->get('/api/departments');

        return $response['data'] ?? [];
    }

    /**
     * Get a single department.
     *
     * @param  int  $departmentKey  The department's primary key
     * @return array|null Department data or null
     *
     * @throws PracticeCsException
     */
    public function findDepartment(int $departmentKey): ?array
    {
        try {
            $response = $this->api->get("/api/departments/{$departmentKey}");

            return $response['data'] ?? null;
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a department.
     *
     * @param  array  $data  Department data (department_id, description)
     * @return array The created department
     *
     * @throws PracticeCsException
     */
    public function createDepartment(array $data): array
    {
        $response = $this->api->post('/api/departments', $data);

        return $response['data'] ?? [];
    }

    /**
     * Update a department.
     *
     * @param  int  $departmentKey  The department's primary key
     * @param  array  $data  Fields to update
     * @return array The updated department
     *
     * @throws PracticeCsException
     */
    public function updateDepartment(int $departmentKey, array $data): array
    {
        $response = $this->api->put("/api/departments/{$departmentKey}", $data);

        return $response['data'] ?? [];
    }

    /**
     * Delete a department.
     *
     * @param  int  $departmentKey  The department's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deleteDepartment(int $departmentKey): bool
    {
        $response = $this->api->delete("/api/departments/{$departmentKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Staff Levels
    // -----------------------------------------------------------------

    /**
     * List all staff levels.
     *
     * @return array[] Array of level arrays
     *
     * @throws PracticeCsException
     */
    public function listLevels(): array
    {
        $response = $this->api->get('/api/staff-levels');

        return $response['data'] ?? [];
    }

    // -----------------------------------------------------------------
    // Billing Rates
    // -----------------------------------------------------------------

    /**
     * Get billing rates for a staff member.
     *
     * @param  int  $staffKey  The staff member's primary key
     * @return array[] Array of billing rate arrays
     *
     * @throws PracticeCsException
     */
    public function listBillingRates(int $staffKey): array
    {
        $response = $this->api->get("/api/staff/{$staffKey}/billing-rates");

        return $response['data'] ?? [];
    }

    // -----------------------------------------------------------------
    // Target Ranges
    // -----------------------------------------------------------------

    /**
     * Get target ranges for a staff member.
     *
     * @param  int  $staffKey  The staff member's primary key
     * @return array[] Array of target range arrays
     *
     * @throws PracticeCsException
     */
    public function listTargetRanges(int $staffKey): array
    {
        $response = $this->api->get("/api/staff/{$staffKey}/targets");

        return $response['data'] ?? [];
    }
}
