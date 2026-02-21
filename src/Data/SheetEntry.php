<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Sheet entry (time & expense) data object returned from the PracticeCS API.
 *
 * @property-read int $sheetEntryKey
 * @property-read int|null $clientKey
 * @property-read int|null $engagementKey
 * @property-read int|null $activityKey
 * @property-read string|null $entryDate
 * @property-read int|null $staffBillingRateKey
 * @property-read float|null $units
 * @property-read float|null $unitCost
 * @property-read float|null $unitPrice
 * @property-read float|null $amount
 * @property-read float|null $calculatedAmount
 * @property-read bool $amountIsOverridden
 * @property-read string|null $comment
 * @property-read string|null $billerNote
 * @property-read int $sheetEntryTypeKey
 * @property-read int|null $staffKey
 * @property-read string|null $sheetDate
 * @property-read string|null $approvedDate
 * @property-read int|null $approvedStaffKey
 * @property-read string|null $postedDate
 * @property-read int|null $postedStaffKey
 * @property-read int|null $projectKey
 * @property-read int|null $taskKey
 * @property-read bool $complete
 * @property-read int $integrationApplicationKey
 * @property-read array|null $openValueCache
 * @property-read Timer[] $timers
 */
class SheetEntry
{
    /**
     * @param  Timer[]  $timers
     */
    public function __construct(
        public readonly int $sheetEntryKey,
        public readonly ?int $clientKey = null,
        public readonly ?int $engagementKey = null,
        public readonly ?int $activityKey = null,
        public readonly ?string $entryDate = null,
        public readonly ?int $staffBillingRateKey = null,
        public readonly ?float $units = null,
        public readonly ?float $unitCost = null,
        public readonly ?float $unitPrice = null,
        public readonly ?float $amount = null,
        public readonly ?float $calculatedAmount = null,
        public readonly bool $amountIsOverridden = false,
        public readonly ?string $comment = null,
        public readonly ?string $billerNote = null,
        public readonly int $sheetEntryTypeKey = 1,
        public readonly ?int $staffKey = null,
        public readonly ?string $sheetDate = null,
        public readonly ?string $approvedDate = null,
        public readonly ?int $approvedStaffKey = null,
        public readonly ?string $postedDate = null,
        public readonly ?int $postedStaffKey = null,
        public readonly ?int $projectKey = null,
        public readonly ?int $taskKey = null,
        public readonly bool $complete = false,
        public readonly int $integrationApplicationKey = 0,
        public readonly ?array $openValueCache = null,
        public readonly array $timers = [],
    ) {}

    /**
     * Create a SheetEntry from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        $timers = [];
        if (! empty($data['timers'])) {
            $timers = array_map(
                fn (array $t) => Timer::fromArray($t),
                $data['timers']
            );
        }

        return new self(
            sheetEntryKey: (int) $data['sheet_entry_KEY'],
            clientKey: isset($data['client_KEY']) ? (int) $data['client_KEY'] : null,
            engagementKey: isset($data['engagement_KEY']) ? (int) $data['engagement_KEY'] : null,
            activityKey: isset($data['activity_KEY']) ? (int) $data['activity_KEY'] : null,
            entryDate: $data['entry_date'] ?? null,
            staffBillingRateKey: isset($data['staff_billing_rate_KEY']) ? (int) $data['staff_billing_rate_KEY'] : null,
            units: isset($data['units']) ? (float) $data['units'] : null,
            unitCost: isset($data['unit_cost']) ? (float) $data['unit_cost'] : null,
            unitPrice: isset($data['unit_price']) ? (float) $data['unit_price'] : null,
            amount: isset($data['amount']) ? (float) $data['amount'] : null,
            calculatedAmount: isset($data['calculated_amount']) ? (float) $data['calculated_amount'] : null,
            amountIsOverridden: (bool) ($data['amount_is_overridden'] ?? false),
            comment: $data['comment'] ?? null,
            billerNote: $data['biller_note'] ?? null,
            sheetEntryTypeKey: (int) ($data['sheet_entry_type_KEY'] ?? 1),
            staffKey: isset($data['staff_KEY']) ? (int) $data['staff_KEY'] : null,
            sheetDate: $data['sheet_date'] ?? null,
            approvedDate: $data['approved_date'] ?? null,
            approvedStaffKey: isset($data['approved__staff_KEY']) ? (int) $data['approved__staff_KEY'] : null,
            postedDate: $data['posted_date'] ?? null,
            postedStaffKey: isset($data['posted__staff_KEY']) ? (int) $data['posted__staff_KEY'] : null,
            projectKey: isset($data['project_KEY']) ? (int) $data['project_KEY'] : null,
            taskKey: isset($data['task_KEY']) ? (int) $data['task_KEY'] : null,
            complete: (bool) ($data['complete'] ?? false),
            integrationApplicationKey: (int) ($data['integration_application_KEY'] ?? 0),
            openValueCache: $data['open_value_cache'] ?? null,
            timers: $timers,
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'sheet_entry_KEY' => $this->sheetEntryKey,
            'client_KEY' => $this->clientKey,
            'engagement_KEY' => $this->engagementKey,
            'activity_KEY' => $this->activityKey,
            'entry_date' => $this->entryDate,
            'staff_billing_rate_KEY' => $this->staffBillingRateKey,
            'units' => $this->units,
            'unit_cost' => $this->unitCost,
            'unit_price' => $this->unitPrice,
            'amount' => $this->amount,
            'calculated_amount' => $this->calculatedAmount,
            'amount_is_overridden' => $this->amountIsOverridden,
            'comment' => $this->comment,
            'biller_note' => $this->billerNote,
            'sheet_entry_type_KEY' => $this->sheetEntryTypeKey,
            'staff_KEY' => $this->staffKey,
            'sheet_date' => $this->sheetDate,
            'approved_date' => $this->approvedDate,
            'approved__staff_KEY' => $this->approvedStaffKey,
            'posted_date' => $this->postedDate,
            'posted__staff_KEY' => $this->postedStaffKey,
            'project_KEY' => $this->projectKey,
            'task_KEY' => $this->taskKey,
            'complete' => $this->complete,
            'integration_application_KEY' => $this->integrationApplicationKey,
            'open_value_cache' => $this->openValueCache,
            'timers' => array_map(fn (Timer $t) => $t->toArray(), $this->timers),
        ];
    }
}
