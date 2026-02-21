<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Invoice;
use FoleyBridgeSolutions\PracticeCsPI\Data\InvoiceApplication;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Invoice operations against the PracticeCS API.
 *
 * Maps to PaymentRepository invoice methods in TR-Pay.
 */
class InvoiceService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new invoice service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Get open invoices for a client.
     *
     * @param  int|null  $clientKey  The client_KEY
     * @param  string|null  $clientId  The client_id (alternative lookup)
     * @return Invoice[] Array of Invoice DTOs
     *
     * @throws PracticeCsException
     */
    public function getOpenInvoices(?int $clientKey = null, ?string $clientId = null): array
    {
        $query = [];
        if ($clientKey !== null) {
            $query['client_KEY'] = $clientKey;
        }
        if ($clientId !== null) {
            $query['client_id'] = $clientId;
        }

        $response = $this->api->get('/api/invoices/open', $query);

        return array_map(
            fn (array $item) => Invoice::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all invoices (open, paid, partially paid) with pagination and filters.
     *
     * @param  array  $filters  Supported keys: client_KEY, client_id, status (open|paid|partial),
     *                          from, to, search, limit, offset
     * @return array{data: Invoice[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function getInvoices(array $filters = []): array
    {
        $response = $this->api->get('/api/invoices', $filters);

        $invoices = array_map(
            fn (array $item) => Invoice::fromArray($item),
            $response['data'] ?? []
        );

        return [
            'data' => $invoices,
            'meta' => $response['meta'] ?? ['total' => 0, 'limit' => 50, 'offset' => 0],
        ];
    }

    /**
     * Get a single invoice by its ledger_entry_KEY.
     *
     * @param  int  $ledgerEntryKey  The ledger entry key
     * @return Invoice|null The invoice, or null if not found
     *
     * @throws PracticeCsException
     */
    public function getInvoice(int $ledgerEntryKey): ?Invoice
    {
        try {
            $response = $this->api->get("/api/invoices/{$ledgerEntryKey}");

            if (empty($response['data'])) {
                return null;
            }

            return Invoice::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            // If the API returns 404, return null instead of throwing
            if (str_contains($e->getMessage(), '404') || str_contains($e->getMessage(), 'not found')) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Get payments applied to a specific invoice.
     *
     * @param  int  $ledgerEntryKey  The invoice's ledger_entry_KEY
     * @return InvoiceApplication[] Array of InvoiceApplication DTOs
     *
     * @throws PracticeCsException
     */
    public function getInvoiceApplications(int $ledgerEntryKey): array
    {
        $response = $this->api->get("/api/invoices/{$ledgerEntryKey}/applications");

        return array_map(
            fn (array $item) => InvoiceApplication::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get grouped invoices for a client (includes group member invoices).
     *
     * Returns raw PracticeCS data. Filtering against local project_acceptances
     * should be done in the consuming application (TR-Pay).
     *
     * @param  int|null  $clientKey  The client_KEY
     * @param  array  $clientInfo  Client information for group resolution
     * @param  string|null  $clientId  The client_id (alternative lookup)
     * @return array Raw grouped invoice data from the API
     *
     * @throws PracticeCsException
     */
    public function getGroupedInvoices(?int $clientKey, array $clientInfo, ?string $clientId = null): array
    {
        $data = [
            'client_info' => $clientInfo,
        ];

        if ($clientKey !== null) {
            $data['client_KEY'] = $clientKey;
        }
        if ($clientId !== null) {
            $data['client_id'] = $clientId;
        }

        $response = $this->api->post('/api/invoices/grouped', $data);

        return $response['data'] ?? [];
    }
}
