<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Ledger entry subtype lookup data from the PracticeCS API.
 *
 * @property-read int $ledgerEntrySubtypeKey
 * @property-read int $ledgerEntryTypeKey
 * @property-read string $description
 */
class LedgerEntrySubtype
{
    public function __construct(
        public readonly int $ledgerEntrySubtypeKey,
        public readonly int $ledgerEntryTypeKey,
        public readonly string $description,
    ) {}

    /**
     * Create a LedgerEntrySubtype from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            ledgerEntrySubtypeKey: (int) $data['ledger_entry_subtype_KEY'],
            ledgerEntryTypeKey: (int) $data['ledger_entry_type_KEY'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'ledger_entry_subtype_KEY' => $this->ledgerEntrySubtypeKey,
            'ledger_entry_type_KEY' => $this->ledgerEntryTypeKey,
            'description' => $this->description,
        ];
    }
}
