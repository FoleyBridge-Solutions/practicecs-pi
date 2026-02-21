<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Payment application data for an invoice.
 *
 * Represents a single payment applied to an invoice via the
 * Ledger_Entry_Application table in PracticeCS.
 *
 * @property-read int $ledgerEntryApplicationKey
 * @property-read int $paymentLedgerEntryKey
 * @property-read int|null $paymentEntryNumber
 * @property-read string|null $paymentDate
 * @property-read string|null $paymentType
 * @property-read string|null $paymentReference
 * @property-read float $appliedAmount
 */
class InvoiceApplication
{
    public function __construct(
        public readonly int $ledgerEntryApplicationKey,
        public readonly int $paymentLedgerEntryKey,
        public readonly ?int $paymentEntryNumber = null,
        public readonly ?string $paymentDate = null,
        public readonly ?string $paymentType = null,
        public readonly ?string $paymentReference = null,
        public readonly float $appliedAmount = 0.00,
    ) {}

    /**
     * Create an InvoiceApplication from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            ledgerEntryApplicationKey: (int) $data['ledger_entry_application_KEY'],
            paymentLedgerEntryKey: (int) $data['payment_ledger_entry_KEY'],
            paymentEntryNumber: isset($data['payment_entry_number']) ? (int) $data['payment_entry_number'] : null,
            paymentDate: $data['payment_date'] ?? null,
            paymentType: $data['payment_type'] ?? null,
            paymentReference: $data['payment_reference'] ?? null,
            appliedAmount: (float) ($data['applied_amount'] ?? 0),
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'ledger_entry_application_KEY' => $this->ledgerEntryApplicationKey,
            'payment_ledger_entry_KEY' => $this->paymentLedgerEntryKey,
            'payment_entry_number' => $this->paymentEntryNumber,
            'payment_date' => $this->paymentDate,
            'payment_type' => $this->paymentType,
            'payment_reference' => $this->paymentReference,
            'applied_amount' => number_format($this->appliedAmount, 2, '.', ''),
        ];
    }
}
