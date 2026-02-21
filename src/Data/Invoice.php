<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Invoice data object returned from the PracticeCS API.
 *
 * @property-read int $ledgerEntryKey
 * @property-read int|null $invoiceNumber
 * @property-read string|null $invoiceDate
 * @property-read string|null $dueDate
 * @property-read string|null $type
 * @property-read float $openAmount
 * @property-read float|null $amount
 * @property-read string|null $status
 * @property-read string|null $description
 * @property-read int|null $clientKey
 * @property-read string|null $clientId
 * @property-read string|null $clientName
 * @property-read bool $isOtherClient
 * @property-read string|null $primaryClientName
 * @property-read string|null $reference
 * @property-read string|null $comments
 * @property-read string|null $postedDate
 * @property-read string|null $billedDate
 * @property-read float|null $timeAmountBilled
 * @property-read float|null $timeAmountAdjusted
 * @property-read float|null $expenseAmountBilled
 * @property-read float|null $expenseAmountAdjusted
 * @property-read float|null $progressAmountBilled
 * @property-read float|null $surcharge
 * @property-read float|null $discount
 * @property-read float|null $salesTax
 * @property-read float|null $serviceTax
 */
class Invoice
{
    public function __construct(
        public readonly int $ledgerEntryKey,
        public readonly ?int $invoiceNumber = null,
        public readonly ?string $invoiceDate = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $type = null,
        public readonly float $openAmount = 0.00,
        public readonly ?float $amount = null,
        public readonly ?string $status = null,
        public readonly ?string $description = null,
        public readonly ?int $clientKey = null,
        public readonly ?string $clientId = null,
        public readonly ?string $clientName = null,
        public readonly bool $isOtherClient = false,
        public readonly ?string $primaryClientName = null,
        public readonly ?string $reference = null,
        public readonly ?string $comments = null,
        public readonly ?string $postedDate = null,
        public readonly ?string $billedDate = null,
        public readonly ?float $timeAmountBilled = null,
        public readonly ?float $timeAmountAdjusted = null,
        public readonly ?float $expenseAmountBilled = null,
        public readonly ?float $expenseAmountAdjusted = null,
        public readonly ?float $progressAmountBilled = null,
        public readonly ?float $surcharge = null,
        public readonly ?float $discount = null,
        public readonly ?float $salesTax = null,
        public readonly ?float $serviceTax = null,
    ) {}

    /**
     * Create an Invoice from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            ledgerEntryKey: (int) $data['ledger_entry_KEY'],
            invoiceNumber: isset($data['invoice_number']) ? (int) $data['invoice_number'] : null,
            invoiceDate: $data['invoice_date'] ?? null,
            dueDate: $data['due_date'] ?? null,
            type: $data['type'] ?? null,
            openAmount: (float) ($data['open_amount'] ?? 0),
            amount: isset($data['amount']) ? (float) $data['amount'] : null,
            status: $data['status'] ?? null,
            description: $data['description'] ?? null,
            clientKey: isset($data['client_KEY']) ? (int) $data['client_KEY'] : null,
            clientId: $data['client_id'] ?? null,
            clientName: $data['client_name'] ?? null,
            isOtherClient: (bool) ($data['is_other_client'] ?? false),
            primaryClientName: $data['primary_client_name'] ?? null,
            reference: $data['reference'] ?? null,
            comments: $data['comments'] ?? null,
            postedDate: $data['posted_date'] ?? null,
            billedDate: $data['billed_date'] ?? null,
            timeAmountBilled: isset($data['time_amount_billed']) ? (float) $data['time_amount_billed'] : null,
            timeAmountAdjusted: isset($data['time_amount_adjusted']) ? (float) $data['time_amount_adjusted'] : null,
            expenseAmountBilled: isset($data['expense_amount_billed']) ? (float) $data['expense_amount_billed'] : null,
            expenseAmountAdjusted: isset($data['expense_amount_adjusted']) ? (float) $data['expense_amount_adjusted'] : null,
            progressAmountBilled: isset($data['progress_amount_billed']) ? (float) $data['progress_amount_billed'] : null,
            surcharge: isset($data['surcharge']) ? (float) $data['surcharge'] : null,
            discount: isset($data['discount']) ? (float) $data['discount'] : null,
            salesTax: isset($data['sales_tax']) ? (float) $data['sales_tax'] : null,
            serviceTax: isset($data['service_tax']) ? (float) $data['service_tax'] : null,
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        $data = [
            'ledger_entry_KEY' => $this->ledgerEntryKey,
            'invoice_number' => $this->invoiceNumber,
            'invoice_date' => $this->invoiceDate,
            'due_date' => $this->dueDate,
            'type' => $this->type,
            'open_amount' => number_format($this->openAmount, 2, '.', ''),
            'description' => $this->description,
            'client_KEY' => $this->clientKey,
            'client_id' => $this->clientId,
            'client_name' => $this->clientName,
            'is_other_client' => $this->isOtherClient,
            'primary_client_name' => $this->primaryClientName,
        ];

        // Include extended fields only when they are set
        if ($this->amount !== null) {
            $data['amount'] = number_format($this->amount, 2, '.', '');
        }
        if ($this->status !== null) {
            $data['status'] = $this->status;
        }
        if ($this->reference !== null) {
            $data['reference'] = $this->reference;
        }
        if ($this->comments !== null) {
            $data['comments'] = $this->comments;
        }
        if ($this->postedDate !== null) {
            $data['posted_date'] = $this->postedDate;
        }
        if ($this->billedDate !== null) {
            $data['billed_date'] = $this->billedDate;
        }
        if ($this->timeAmountBilled !== null) {
            $data['time_amount_billed'] = number_format($this->timeAmountBilled, 2, '.', '');
        }
        if ($this->timeAmountAdjusted !== null) {
            $data['time_amount_adjusted'] = number_format($this->timeAmountAdjusted, 2, '.', '');
        }
        if ($this->expenseAmountBilled !== null) {
            $data['expense_amount_billed'] = number_format($this->expenseAmountBilled, 2, '.', '');
        }
        if ($this->expenseAmountAdjusted !== null) {
            $data['expense_amount_adjusted'] = number_format($this->expenseAmountAdjusted, 2, '.', '');
        }
        if ($this->progressAmountBilled !== null) {
            $data['progress_amount_billed'] = number_format($this->progressAmountBilled, 2, '.', '');
        }
        if ($this->surcharge !== null) {
            $data['surcharge'] = number_format($this->surcharge, 2, '.', '');
        }
        if ($this->discount !== null) {
            $data['discount'] = number_format($this->discount, 2, '.', '');
        }
        if ($this->salesTax !== null) {
            $data['sales_tax'] = number_format($this->salesTax, 2, '.', '');
        }
        if ($this->serviceTax !== null) {
            $data['service_tax'] = number_format($this->serviceTax, 2, '.', '');
        }

        return $data;
    }
}
