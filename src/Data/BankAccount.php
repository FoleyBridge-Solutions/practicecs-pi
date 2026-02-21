<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Bank account lookup data from the PracticeCS API.
 *
 * @property-read int $bankAccountKey
 * @property-read string $bankAccountId
 * @property-read string $description
 */
class BankAccount
{
    public function __construct(
        public readonly int $bankAccountKey,
        public readonly string $bankAccountId,
        public readonly string $description,
    ) {}

    /**
     * Create a BankAccount from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            bankAccountKey: (int) $data['bank_account_KEY'],
            bankAccountId: $data['bank_account_id'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'bank_account_KEY' => $this->bankAccountKey,
            'bank_account_id' => $this->bankAccountId,
            'description' => $this->description,
        ];
    }
}
