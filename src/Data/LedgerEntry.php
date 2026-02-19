<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Ledger entry result returned after writing to PracticeCS.
 *
 * @property-read bool $success
 * @property-read int|null $ledgerEntryKey
 * @property-read int|null $entryNumber
 * @property-read string|null $error
 * @property-read string|null $warning
 */
class LedgerEntry
{
    public function __construct(
        public readonly bool $success,
        public readonly ?int $ledgerEntryKey = null,
        public readonly ?int $entryNumber = null,
        public readonly ?string $error = null,
        public readonly ?string $warning = null,
    ) {}

    /**
     * Create a LedgerEntry from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            success: (bool) $data['success'],
            ledgerEntryKey: isset($data['ledger_entry_KEY']) ? (int) $data['ledger_entry_KEY'] : null,
            entryNumber: isset($data['entry_number']) ? (int) $data['entry_number'] : null,
            error: $data['error'] ?? null,
            warning: $data['warning'] ?? null,
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'ledger_entry_KEY' => $this->ledgerEntryKey,
            'entry_number' => $this->entryNumber,
            'error' => $this->error,
            'warning' => $this->warning,
        ];
    }
}
