<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Credit card type lookup data from the PracticeCS API.
 *
 * @property-read int $creditCardTypeKey
 * @property-read string $creditCardId
 * @property-read string $description
 */
class CreditCardType
{
    public function __construct(
        public readonly int $creditCardTypeKey,
        public readonly string $creditCardId,
        public readonly string $description,
    ) {}

    /**
     * Create a CreditCardType from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            creditCardTypeKey: (int) $data['credit_card_type_KEY'],
            creditCardId: $data['credit_card_id'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'credit_card_type_KEY' => $this->creditCardTypeKey,
            'credit_card_id' => $this->creditCardId,
            'description' => $this->description,
        ];
    }
}
