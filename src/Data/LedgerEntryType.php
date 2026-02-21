<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Ledger entry type lookup data from the PracticeCS API.
 *
 * @property-read int $ledgerEntryTypeKey
 * @property-read string $ledgerEntryTypeId
 * @property-read string $description
 * @property-read int $normalSign
 */
class LedgerEntryType
{
    public function __construct(
        public readonly int $ledgerEntryTypeKey,
        public readonly string $ledgerEntryTypeId,
        public readonly string $description,
        public readonly int $normalSign,
    ) {}

    /**
     * Create a LedgerEntryType from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            ledgerEntryTypeKey: (int) $data['ledger_entry_type_KEY'],
            ledgerEntryTypeId: $data['ledger_entry_type_id'],
            description: $data['description'],
            normalSign: (int) $data['normal_sign'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'ledger_entry_type_KEY' => $this->ledgerEntryTypeKey,
            'ledger_entry_type_id' => $this->ledgerEntryTypeId,
            'description' => $this->description,
            'normal_sign' => $this->normalSign,
        ];
    }
}
