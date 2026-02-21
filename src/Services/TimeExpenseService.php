<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\SheetEntry;
use FoleyBridgeSolutions\PracticeCsPI\Data\Timer;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Time & expense operations against the PracticeCS API.
 *
 * Full CRUD for sheet entries (time/expense records) and timers.
 */
class TimeExpenseService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new time/expense service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Sheet Entries
    // -----------------------------------------------------------------

    /**
     * List or search sheet entries.
     *
     * @param  array  $filters  Associative array of filters (client_KEY, staff_KEY, from, to, etc.)
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: SheetEntry[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function list(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $query = array_merge($filters, ['limit' => $limit, 'offset' => $offset]);

        $response = $this->api->get('/api/sheet-entries', $query);

        return [
            'data' => array_map(
                fn (array $item) => SheetEntry::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single sheet entry with open values and timers.
     *
     * @param  int  $entryKey  The sheet entry's primary key
     * @return SheetEntry|null SheetEntry DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function find(int $entryKey): ?SheetEntry
    {
        try {
            $response = $this->api->get("/api/sheet-entries/{$entryKey}");

            if (empty($response['data'])) {
                return null;
            }

            return SheetEntry::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Get sheet entries for a specific client.
     *
     * @param  int  $clientKey  The client's primary key
     * @param  string|null  $from  Start date filter (Y-m-d)
     * @param  string|null  $to  End date filter (Y-m-d)
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: SheetEntry[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function findByClient(
        int $clientKey,
        ?string $from = null,
        ?string $to = null,
        int $limit = 50,
        int $offset = 0
    ): array {
        $query = ['limit' => $limit, 'offset' => $offset];

        if ($from !== null) {
            $query['from'] = $from;
        }
        if ($to !== null) {
            $query['to'] = $to;
        }

        $response = $this->api->get("/api/sheet-entries/by-client/{$clientKey}", $query);

        return [
            'data' => array_map(
                fn (array $item) => SheetEntry::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get sheet entries for a specific staff member.
     *
     * @param  int  $staffKey  The staff member's primary key
     * @param  string|null  $from  Start date filter (Y-m-d)
     * @param  string|null  $to  End date filter (Y-m-d)
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: SheetEntry[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function findByStaff(
        int $staffKey,
        ?string $from = null,
        ?string $to = null,
        int $limit = 50,
        int $offset = 0
    ): array {
        $query = ['limit' => $limit, 'offset' => $offset];

        if ($from !== null) {
            $query['from'] = $from;
        }
        if ($to !== null) {
            $query['to'] = $to;
        }

        $response = $this->api->get("/api/sheet-entries/by-staff/{$staffKey}", $query);

        return [
            'data' => array_map(
                fn (array $item) => SheetEntry::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Create a new sheet entry.
     *
     * @param  array  $data  Sheet entry data
     * @return SheetEntry The created sheet entry
     *
     * @throws PracticeCsException
     */
    public function create(array $data): SheetEntry
    {
        $response = $this->api->post('/api/sheet-entries', $data);

        return SheetEntry::fromArray($response['data']);
    }

    /**
     * Update a sheet entry.
     *
     * @param  int  $entryKey  The sheet entry's primary key
     * @param  array  $data  Fields to update
     * @return SheetEntry The updated sheet entry
     *
     * @throws PracticeCsException
     */
    public function update(int $entryKey, array $data): SheetEntry
    {
        $response = $this->api->put("/api/sheet-entries/{$entryKey}", $data);

        return SheetEntry::fromArray($response['data']);
    }

    /**
     * Delete a sheet entry (and its timers).
     *
     * @param  int  $entryKey  The sheet entry's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function delete(int $entryKey): bool
    {
        $response = $this->api->delete("/api/sheet-entries/{$entryKey}");

        return $response['data']['deleted'] ?? false;
    }

    /**
     * Get the open value cache for a sheet entry.
     *
     * @param  int  $entryKey  The sheet entry's primary key
     * @return array|null Open value data or null
     *
     * @throws PracticeCsException
     */
    public function getOpenValues(int $entryKey): ?array
    {
        try {
            $response = $this->api->get("/api/sheet-entries/{$entryKey}/open-values");

            return $response['data'] ?? null;
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    // -----------------------------------------------------------------
    // Timers
    // -----------------------------------------------------------------

    /**
     * List timers, optionally filtered.
     *
     * @param  array  $filters  Associative array of filters (sheet_entry_KEY, user_name)
     * @param  int  $limit  Maximum results
     * @param  int  $offset  Offset for pagination
     * @return array{data: Timer[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function listTimers(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $query = array_merge($filters, ['limit' => $limit, 'offset' => $offset]);

        $response = $this->api->get('/api/timers', $query);

        return [
            'data' => array_map(
                fn (array $item) => Timer::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single timer.
     *
     * @param  int  $timerKey  The timer's primary key
     * @return Timer|null Timer DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function findTimer(int $timerKey): ?Timer
    {
        try {
            $response = $this->api->get("/api/timers/{$timerKey}");

            if (empty($response['data'])) {
                return null;
            }

            return Timer::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Get timers for a specific sheet entry.
     *
     * @param  int  $entryKey  The sheet entry's primary key
     * @return Timer[] Array of Timer DTOs
     *
     * @throws PracticeCsException
     */
    public function getEntryTimers(int $entryKey): array
    {
        $response = $this->api->get("/api/sheet-entries/{$entryKey}/timers");

        return array_map(
            fn (array $item) => Timer::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Create a new timer.
     *
     * @param  array  $data  Timer data
     * @return Timer The created timer
     *
     * @throws PracticeCsException
     */
    public function createTimer(array $data): Timer
    {
        $response = $this->api->post('/api/timers', $data);

        return Timer::fromArray($response['data']);
    }

    /**
     * Update a timer.
     *
     * @param  int  $timerKey  The timer's primary key
     * @param  array  $data  Fields to update
     * @return Timer The updated timer
     *
     * @throws PracticeCsException
     */
    public function updateTimer(int $timerKey, array $data): Timer
    {
        $response = $this->api->put("/api/timers/{$timerKey}", $data);

        return Timer::fromArray($response['data']);
    }

    /**
     * Delete a timer.
     *
     * @param  int  $timerKey  The timer's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deleteTimer(int $timerKey): bool
    {
        $response = $this->api->delete("/api/timers/{$timerKey}");

        return $response['data']['deleted'] ?? false;
    }
}
