<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Invoice;
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
