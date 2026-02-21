<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\BankAccount;
use FoleyBridgeSolutions\PracticeCsPI\Data\BillingRateType;
use FoleyBridgeSolutions\PracticeCsPI\Data\CalendarCategory;
use FoleyBridgeSolutions\PracticeCsPI\Data\ClientStatus;
use FoleyBridgeSolutions\PracticeCsPI\Data\CreditCardType;
use FoleyBridgeSolutions\PracticeCsPI\Data\EducationAccountancy;
use FoleyBridgeSolutions\PracticeCsPI\Data\EventClassType;
use FoleyBridgeSolutions\PracticeCsPI\Data\LedgerEntrySubtype;
use FoleyBridgeSolutions\PracticeCsPI\Data\LedgerEntryType;
use FoleyBridgeSolutions\PracticeCsPI\Data\LostReason;
use FoleyBridgeSolutions\PracticeCsPI\Data\LostTo;
use FoleyBridgeSolutions\PracticeCsPI\Data\Priority;
use FoleyBridgeSolutions\PracticeCsPI\Data\RecurrenceMethod;
use FoleyBridgeSolutions\PracticeCsPI\Data\ReferralSource;
use FoleyBridgeSolutions\PracticeCsPI\Data\StaffGroupingType;
use FoleyBridgeSolutions\PracticeCsPI\Data\WonReason;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Read-only lookup/reference data from PracticeCS.
 *
 * Provides access to all reference tables (billing rate types, priorities,
 * credit card types, calendar categories, etc.).
 */
class LookupService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new lookup service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Get all billing rate types.
     *
     * @return BillingRateType[] Array of BillingRateType DTOs
     *
     * @throws PracticeCsException
     */
    public function billingRateTypes(): array
    {
        $response = $this->api->get('/api/lookup/billing-rate-types');

        return array_map(
            fn (array $item) => BillingRateType::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all credit card types.
     *
     * @return CreditCardType[] Array of CreditCardType DTOs
     *
     * @throws PracticeCsException
     */
    public function creditCardTypes(): array
    {
        $response = $this->api->get('/api/lookup/credit-card-types');

        return array_map(
            fn (array $item) => CreditCardType::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all priorities.
     *
     * @return Priority[] Array of Priority DTOs
     *
     * @throws PracticeCsException
     */
    public function priorities(): array
    {
        $response = $this->api->get('/api/lookup/priorities');

        return array_map(
            fn (array $item) => Priority::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all event class types.
     *
     * @return EventClassType[] Array of EventClassType DTOs
     *
     * @throws PracticeCsException
     */
    public function eventClassTypes(): array
    {
        $response = $this->api->get('/api/lookup/event-class-types');

        return array_map(
            fn (array $item) => EventClassType::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all recurrence methods.
     *
     * @return RecurrenceMethod[] Array of RecurrenceMethod DTOs
     *
     * @throws PracticeCsException
     */
    public function recurrenceMethods(): array
    {
        $response = $this->api->get('/api/lookup/recurrence-methods');

        return array_map(
            fn (array $item) => RecurrenceMethod::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all calendar categories.
     *
     * @return CalendarCategory[] Array of CalendarCategory DTOs
     *
     * @throws PracticeCsException
     */
    public function calendarCategories(): array
    {
        $response = $this->api->get('/api/lookup/calendar-categories');

        return array_map(
            fn (array $item) => CalendarCategory::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all education accountancies.
     *
     * @return EducationAccountancy[] Array of EducationAccountancy DTOs
     *
     * @throws PracticeCsException
     */
    public function educationAccountancies(): array
    {
        $response = $this->api->get('/api/lookup/education-accountancies');

        return array_map(
            fn (array $item) => EducationAccountancy::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all lost reasons.
     *
     * @return LostReason[] Array of LostReason DTOs
     *
     * @throws PracticeCsException
     */
    public function lostReasons(): array
    {
        $response = $this->api->get('/api/lookup/lost-reasons');

        return array_map(
            fn (array $item) => LostReason::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all lost-to entries.
     *
     * @return LostTo[] Array of LostTo DTOs
     *
     * @throws PracticeCsException
     */
    public function lostTos(): array
    {
        $response = $this->api->get('/api/lookup/lost-tos');

        return array_map(
            fn (array $item) => LostTo::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all won reasons.
     *
     * @return WonReason[] Array of WonReason DTOs
     *
     * @throws PracticeCsException
     */
    public function wonReasons(): array
    {
        $response = $this->api->get('/api/lookup/won-reasons');

        return array_map(
            fn (array $item) => WonReason::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all referral sources.
     *
     * @return ReferralSource[] Array of ReferralSource DTOs
     *
     * @throws PracticeCsException
     */
    public function referralSources(): array
    {
        $response = $this->api->get('/api/lookup/referral-sources');

        return array_map(
            fn (array $item) => ReferralSource::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all staff grouping types.
     *
     * @return StaffGroupingType[] Array of StaffGroupingType DTOs
     *
     * @throws PracticeCsException
     */
    public function staffGroupingTypes(): array
    {
        $response = $this->api->get('/api/lookup/staff-grouping-types');

        return array_map(
            fn (array $item) => StaffGroupingType::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all ledger entry types.
     *
     * @return LedgerEntryType[] Array of LedgerEntryType DTOs
     *
     * @throws PracticeCsException
     */
    public function ledgerEntryTypes(): array
    {
        $response = $this->api->get('/api/lookup/ledger-entry-types');

        return array_map(
            fn (array $item) => LedgerEntryType::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all ledger entry subtypes.
     *
     * @return LedgerEntrySubtype[] Array of LedgerEntrySubtype DTOs
     *
     * @throws PracticeCsException
     */
    public function ledgerEntrySubtypes(): array
    {
        $response = $this->api->get('/api/lookup/ledger-entry-subtypes');

        return array_map(
            fn (array $item) => LedgerEntrySubtype::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all client statuses.
     *
     * @return ClientStatus[] Array of ClientStatus DTOs
     *
     * @throws PracticeCsException
     */
    public function clientStatuses(): array
    {
        $response = $this->api->get('/api/lookup/client-statuses');

        return array_map(
            fn (array $item) => ClientStatus::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get all bank accounts.
     *
     * @return BankAccount[] Array of BankAccount DTOs
     *
     * @throws PracticeCsException
     */
    public function bankAccounts(): array
    {
        $response = $this->api->get('/api/lookup/bank-accounts');

        return array_map(
            fn (array $item) => BankAccount::fromArray($item),
            $response['data'] ?? []
        );
    }
}
