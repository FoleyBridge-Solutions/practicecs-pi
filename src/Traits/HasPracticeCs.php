<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Traits;

use FoleyBridgeSolutions\PracticeCsPI\Data\Balance;
use FoleyBridgeSolutions\PracticeCsPI\Data\Client;
use FoleyBridgeSolutions\PracticeCsPI\Data\Invoice;
use FoleyBridgeSolutions\PracticeCsPI\Services\ClientService;
use FoleyBridgeSolutions\PracticeCsPI\Services\InvoiceService;

/**
 * Trait for Eloquent models that have a PracticeCS client association.
 *
 * Add this trait to any model that has a `client_id` attribute linking
 * it to a PracticeCS client. Provides convenient accessor methods
 * for fetching PracticeCS data via the API.
 *
 * Usage:
 *   class PaymentPlan extends Model
 *   {
 *       use HasPracticeCs;
 *
 *       // Override if your column is named differently
 *       public function getPracticecsClientId(): string
 *       {
 *           return $this->client_id;
 *       }
 *   }
 */
trait HasPracticeCs
{
    /**
     * Get the PracticeCS client_id for this model.
     *
     * Override this method if your model uses a different column name.
     *
     * @return string
     */
    public function getPracticecsClientId(): string
    {
        return (string) $this->client_id;
    }

    /**
     * Get the PracticeCS client data for this model.
     *
     * @return Client|null
     */
    public function getPracticecsClient(): ?Client
    {
        return app(ClientService::class)->findByClientId(
            $this->getPracticecsClientId()
        );
    }

    /**
     * Get the PracticeCS balance for this model's client.
     *
     * @return Balance
     */
    public function getPracticecsBalance(): Balance
    {
        return app(ClientService::class)->getBalance(
            clientId: $this->getPracticecsClientId()
        );
    }

    /**
     * Get open invoices for this model's client.
     *
     * @return Invoice[]
     */
    public function getPracticecsOpenInvoices(): array
    {
        return app(InvoiceService::class)->getOpenInvoices(
            clientId: $this->getPracticecsClientId()
        );
    }

    /**
     * Get the PracticeCS client email for this model.
     *
     * @return string|null
     */
    public function getPracticecsEmail(): ?string
    {
        return app(ClientService::class)->getEmail(
            $this->getPracticecsClientId()
        );
    }
}
