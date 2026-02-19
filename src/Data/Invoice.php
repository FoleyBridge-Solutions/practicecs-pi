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
 * @property-read string|null $description
 * @property-read int|null $clientKey
 * @property-read string|null $clientId
 * @property-read string|null $clientName
 * @property-read bool $isOtherClient
 * @property-read string|null $primaryClientName
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
        public readonly ?string $description = null,
        public readonly ?int $clientKey = null,
        public readonly ?string $clientId = null,
        public readonly ?string $clientName = null,
        public readonly bool $isOtherClient = false,
        public readonly ?string $primaryClientName = null,
    ) {}

    /**
     * Create an Invoice from an API response array.
     *
     * @param array $data API response data
     * @return self
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
            description: $data['description'] ?? null,
            clientKey: isset($data['client_KEY']) ? (int) $data['client_KEY'] : null,
            clientId: $data['client_id'] ?? null,
            clientName: $data['client_name'] ?? null,
            isOtherClient: (bool) ($data['is_other_client'] ?? false),
            primaryClientName: $data['primary_client_name'] ?? null,
        );
    }

    /**
     * Convert to array representation.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
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
    }
}
