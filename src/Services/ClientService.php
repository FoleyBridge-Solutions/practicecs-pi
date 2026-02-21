<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Balance;
use FoleyBridgeSolutions\PracticeCsPI\Data\Client;
use FoleyBridgeSolutions\PracticeCsPI\Data\Contact;
use FoleyBridgeSolutions\PracticeCsPI\Data\Engagement;
use FoleyBridgeSolutions\PracticeCsPI\Data\Invoice;
use FoleyBridgeSolutions\PracticeCsPI\Data\Project;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Client operations against the PracticeCS API.
 *
 * Maps to PaymentRepository client methods in TR-Pay.
 */
class ClientService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new client service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Resolve a client_id to its client_KEY.
     *
     * @param  string  $clientId  The client ID (e.g. "12345")
     * @return int|null The client_KEY, or null if not found
     *
     * @throws PracticeCsException
     */
    public function resolveClientKey(string $clientId): ?int
    {
        $response = $this->api->get('/api/clients/resolve', [
            'client_id' => $clientId,
        ]);

        return $response['data']['client_KEY'] ?? null;
    }

    /**
     * Find a client by client_id.
     *
     * @param  string  $clientId  The client ID
     * @return Client|null Client DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function findByClientId(string $clientId): ?Client
    {
        try {
            $response = $this->api->get("/api/clients/by-id/{$clientId}");

            if (empty($response['data'])) {
                return null;
            }

            return Client::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Search clients by name, client_id, or tax_id.
     *
     * @param  string  $query  Search query string
     * @param  string  $searchType  One of: 'name', 'client_id', 'tax_id'
     * @param  int  $limit  Maximum number of results
     * @return Client[] Array of Client DTOs
     *
     * @throws PracticeCsException
     */
    public function search(string $query, string $searchType = 'name', int $limit = 20): array
    {
        $response = $this->api->get('/api/clients/search', [
            'query' => $query,
            'search_type' => $searchType,
            'limit' => $limit,
        ]);

        return array_map(
            fn (array $item) => Client::fromArray($item),
            $response['data']['results'] ?? []
        );
    }

    /**
     * Get client names for a batch of client IDs.
     *
     * @param  array  $clientIds  Array of client ID strings
     * @return array Associative array of client_id => client_name
     *
     * @throws PracticeCsException
     */
    public function getNames(array $clientIds): array
    {
        $response = $this->api->post('/api/clients/names', [
            'client_ids' => $clientIds,
        ]);

        return $response['data'] ?? [];
    }

    /**
     * Get a single client's name.
     *
     * @param  string  $clientId  The client ID
     * @return string|null The client name, or null if not found
     *
     * @throws PracticeCsException
     */
    public function getName(string $clientId): ?string
    {
        $response = $this->api->get("/api/clients/{$clientId}/name");

        return $response['data']['client_name'] ?? null;
    }

    /**
     * Look up clients by last 4 of tax ID and last name.
     *
     * Returns the multi-client lookup structure from the API, which includes
     * all matching clients and convenience fields for single-match cases.
     *
     * @param  string  $last4  Last 4 digits of federal TIN
     * @param  string  $lastName  Last name to match
     * @return array{clients: array[], client_KEY: int|null, client_id: string|null, client_name: string|null}|null Null if no matches found
     *
     * @throws PracticeCsException
     */
    public function findByTaxIdAndName(string $last4, string $lastName): ?array
    {
        try {
            $response = $this->api->get('/api/clients/lookup', [
                'last4' => $last4,
                'last_name' => $lastName,
            ]);

            $data = $response['data'] ?? [];

            if (empty($data['clients'])) {
                return null;
            }

            return $data;
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Get a client's outstanding balance.
     *
     * @param  int|null  $clientKey  The client_KEY
     * @param  string|null  $clientId  The client_id (alternative lookup)
     * @return Balance Balance DTO
     *
     * @throws PracticeCsException
     */
    public function getBalance(?int $clientKey = null, ?string $clientId = null): Balance
    {
        $query = [];
        if ($clientKey !== null) {
            $query['client_KEY'] = $clientKey;
        }
        if ($clientId !== null) {
            $query['client_id'] = $clientId;
        }

        $response = $this->api->get('/api/clients/balance', $query);

        return Balance::fromArray($response['data']);
    }

    /**
     * Get a client's primary email address.
     *
     * @param  string  $clientId  The client ID
     * @return string|null Email address or null if not found
     *
     * @throws PracticeCsException
     */
    public function getEmail(string $clientId): ?string
    {
        $response = $this->api->get("/api/clients/{$clientId}/email");

        return $response['data']['email'] ?? null;
    }

    /**
     * Get emails for a batch of client IDs.
     *
     * @param  array  $clientIds  Array of client ID strings
     * @return array Associative array of client_id => email
     *
     * @throws PracticeCsException
     */
    public function getEmailsBatch(array $clientIds): array
    {
        $response = $this->api->post('/api/clients/emails', [
            'client_ids' => $clientIds,
        ]);

        return $response['data'] ?? [];
    }

    /**
     * Get group members for a client.
     *
     * Returns the group name and all other clients in the same group,
     * or null if the client is not part of any group.
     *
     * @param  string  $clientId  The client ID
     * @return array{group_name: string, members: array[]}|null Group data or null if not in a group
     *
     * @throws PracticeCsException
     */
    public function getGroupMembers(string $clientId): ?array
    {
        try {
            $response = $this->api->get("/api/clients/{$clientId}/group");

            return $response['data'] ?? null;
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Add a client to a group (or create a new group).
     *
     * If the client already belongs to a group, they are moved to the new one.
     *
     * @param  string  $clientId  The client ID
     * @param  string  $groupName  The group name to assign
     * @return array{group_name: string} The assigned group data
     *
     * @throws PracticeCsException
     */
    public function addToGroup(string $clientId, string $groupName): array
    {
        $response = $this->api->post("/api/clients/{$clientId}/group", [
            'group_name' => $groupName,
        ]);

        return $response['data'];
    }

    /**
     * Remove a client from their group.
     *
     * @param  string  $clientId  The client ID
     * @return bool True if successfully removed
     *
     * @throws PracticeCsException
     */
    public function removeFromGroup(string $clientId): bool
    {
        $this->api->delete("/api/clients/{$clientId}/group");

        return true;
    }

    /**
     * Rename a client group.
     *
     * Updates all clients in the group with the new name.
     *
     * @param  string  $oldName  Current group name
     * @param  string  $newName  New group name
     * @return array{updated: int, group_name: string} Update result
     *
     * @throws PracticeCsException
     */
    public function renameGroup(string $oldName, string $newName): array
    {
        $response = $this->api->put('/api/clients/group/rename', [
            'old_name' => $oldName,
            'new_name' => $newName,
        ]);

        return $response['data'];
    }

    /**
     * List all existing client group names with member counts.
     *
     * @return array[] Array of {group_name: string, member_count: int}
     *
     * @throws PracticeCsException
     */
    public function listGroups(): array
    {
        $response = $this->api->get('/api/clients/groups');

        return $response['data'] ?? [];
    }

    /**
     * Get detailed information about a specific group by name.
     *
     * Returns the group name, all members with balances, and total balance.
     *
     * @param  string  $groupName  The group name to look up
     * @return array{group_name: string, members: array[], total_balance: float}|null Group data or null if not found
     *
     * @throws PracticeCsException
     */
    public function getGroup(string $groupName): ?array
    {
        try {
            $response = $this->api->get('/api/clients/groups/show', [
                'name' => $groupName,
            ]);

            return $response['data'] ?? null;
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * List clients with optional filters and pagination.
     *
     * @param  array  $filters  Optional filters to apply
     * @param  int  $limit  Maximum number of results
     * @param  int  $offset  Offset for pagination
     * @return array{data: Client[], meta: array} Paginated client list
     *
     * @throws PracticeCsException
     */
    public function list(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $response = $this->api->get('/api/clients', array_merge($filters, [
            'limit' => $limit,
            'offset' => $offset,
        ]));

        return [
            'data' => array_map(
                fn (array $item) => Client::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Create a new client.
     *
     * @param  array  $data  Client data to create
     * @return Client The created client
     *
     * @throws PracticeCsException
     */
    public function create(array $data): Client
    {
        $response = $this->api->post('/api/clients', $data);

        return Client::fromArray($response['data']);
    }

    /**
     * Update an existing client.
     *
     * @param  int  $clientKey  The client_KEY to update
     * @param  array  $data  Client data to update
     * @return Client The updated client
     *
     * @throws PracticeCsException
     */
    public function update(int $clientKey, array $data): Client
    {
        $response = $this->api->put("/api/clients/{$clientKey}", $data);

        return Client::fromArray($response['data']);
    }

    /**
     * Delete a client.
     *
     * @param  int  $clientKey  The client_KEY to delete
     * @return bool True if the client was deleted
     *
     * @throws PracticeCsException
     */
    public function delete(int $clientKey): bool
    {
        $response = $this->api->delete("/api/clients/{$clientKey}");

        return $response['data']['deleted'] ?? false;
    }

    /**
     * Get financial data for a client.
     *
     * @param  int  $clientKey  The client_KEY
     * @param  int|null  $taxYear  Optional tax year filter
     * @return array Financial data
     *
     * @throws PracticeCsException
     */
    public function financialData(int $clientKey, ?int $taxYear = null): array
    {
        $query = [];
        if ($taxYear !== null) {
            $query['tax_year'] = $taxYear;
        }

        $response = $this->api->get("/api/clients/{$clientKey}/financial-data", $query);

        return $response['data'] ?? [];
    }

    /**
     * Get service charge information for a client.
     *
     * @param  int  $clientKey  The client_KEY
     * @return array|null Service charge data or null if not found
     *
     * @throws PracticeCsException
     */
    public function serviceCharge(int $clientKey): ?array
    {
        try {
            $response = $this->api->get("/api/clients/{$clientKey}/service-charge");

            return $response['data'] ?? null;
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Get status events for a client.
     *
     * @param  int  $clientKey  The client_KEY
     * @return array Status events
     *
     * @throws PracticeCsException
     */
    public function statusEvents(int $clientKey): array
    {
        $response = $this->api->get("/api/clients/{$clientKey}/status-events");

        return $response['data'] ?? [];
    }

    /**
     * Get staff groupings for a client.
     *
     * @param  int  $clientKey  The client_KEY
     * @return array Staff groupings
     *
     * @throws PracticeCsException
     */
    public function staffGroupings(int $clientKey): array
    {
        $response = $this->api->get("/api/clients/{$clientKey}/staff-groupings");

        return $response['data'] ?? [];
    }

    /**
     * Get invoices for a client with pagination.
     *
     * @param  int  $clientKey  The client_KEY
     * @param  int  $limit  Maximum number of results
     * @param  int  $offset  Offset for pagination
     * @return array{data: array[], meta: array} Paginated invoice list
     *
     * @throws PracticeCsException
     */
    public function clientInvoices(int $clientKey, int $limit = 50, int $offset = 0): array
    {
        $response = $this->api->get("/api/clients/{$clientKey}/invoices", [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return [
            'data' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get projects for a client with pagination.
     *
     * @param  int  $clientKey  The client_KEY
     * @param  int  $limit  Maximum number of results
     * @param  int  $offset  Offset for pagination
     * @return array{data: array[], meta: array} Paginated project list
     *
     * @throws PracticeCsException
     */
    public function clientProjects(int $clientKey, int $limit = 50, int $offset = 0): array
    {
        $response = $this->api->get("/api/clients/{$clientKey}/projects", [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return [
            'data' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get engagements for a client with pagination.
     *
     * @param  int  $clientKey  The client_KEY
     * @param  int  $limit  Maximum number of results
     * @param  int  $offset  Offset for pagination
     * @return array{data: array[], meta: array} Paginated engagement list
     *
     * @throws PracticeCsException
     */
    public function clientEngagements(int $clientKey, int $limit = 50, int $offset = 0): array
    {
        $response = $this->api->get("/api/clients/{$clientKey}/engagements", [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return [
            'data' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get ledger entries for a client with pagination.
     *
     * @param  int  $clientKey  The client_KEY
     * @param  int  $limit  Maximum number of results
     * @param  int  $offset  Offset for pagination
     * @return array{data: array[], meta: array} Paginated ledger entry list
     *
     * @throws PracticeCsException
     */
    public function clientLedgerEntries(int $clientKey, int $limit = 50, int $offset = 0): array
    {
        $response = $this->api->get("/api/clients/{$clientKey}/ledger-entries", [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return [
            'data' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get contacts for a client with pagination.
     *
     * @param  int  $clientKey  The client_KEY
     * @param  int  $limit  Maximum number of results
     * @param  int  $offset  Offset for pagination
     * @return array{data: array[], meta: array} Paginated contact list
     *
     * @throws PracticeCsException
     */
    public function clientContacts(int $clientKey, int $limit = 50, int $offset = 0): array
    {
        $response = $this->api->get("/api/clients/{$clientKey}/contacts", [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return [
            'data' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ];
    }
}
