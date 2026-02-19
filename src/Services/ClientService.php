<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Balance;
use FoleyBridgeSolutions\PracticeCsPI\Data\Client;
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
     * @param ApiClient $api The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Resolve a client_id to its client_KEY.
     *
     * @param string $clientId The client ID (e.g. "12345")
     * @return int|null The client_KEY, or null if not found
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
     * @param string $clientId The client ID
     * @return Client|null Client DTO or null if not found
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
     * @param string $query Search query string
     * @param string $searchType One of: 'name', 'client_id', 'tax_id'
     * @param int $limit Maximum number of results
     * @return Client[] Array of Client DTOs
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
            fn(array $item) => Client::fromArray($item),
            $response['data']['results'] ?? []
        );
    }

    /**
     * Get client names for a batch of client IDs.
     *
     * @param array $clientIds Array of client ID strings
     * @return array Associative array of client_id => client_name
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
     * @param string $clientId The client ID
     * @return string|null The client name, or null if not found
     * @throws PracticeCsException
     */
    public function getName(string $clientId): ?string
    {
        $response = $this->api->get("/api/clients/{$clientId}/name");

        return $response['data']['client_name'] ?? null;
    }

    /**
     * Look up a client by last 4 of tax ID and last name.
     *
     * @param string $last4 Last 4 digits of federal TIN
     * @param string $lastName Last name to match
     * @return Client|null Client DTO or null if not found
     * @throws PracticeCsException
     */
    public function findByTaxIdAndName(string $last4, string $lastName): ?Client
    {
        try {
            $response = $this->api->get('/api/clients/lookup', [
                'last4' => $last4,
                'last_name' => $lastName,
            ]);

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
     * Get a client's outstanding balance.
     *
     * @param int|null $clientKey The client_KEY
     * @param string|null $clientId The client_id (alternative lookup)
     * @return Balance Balance DTO
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
     * @param string $clientId The client ID
     * @return string|null Email address or null if not found
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
     * @param array $clientIds Array of client ID strings
     * @return array Associative array of client_id => email
     * @throws PracticeCsException
     */
    public function getEmailsBatch(array $clientIds): array
    {
        $response = $this->api->post('/api/clients/emails', [
            'client_ids' => $clientIds,
        ]);

        return $response['data'] ?? [];
    }
}
