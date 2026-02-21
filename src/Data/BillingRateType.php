<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Billing rate type lookup data from the PracticeCS API.
 *
 * @property-read int $billingRateTypeKey
 * @property-read string $description
 */
class BillingRateType
{
    public function __construct(
        public readonly int $billingRateTypeKey,
        public readonly string $description,
    ) {}

    /**
     * Create a BillingRateType from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            billingRateTypeKey: (int) $data['billing_rate_type_KEY'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'billing_rate_type_KEY' => $this->billingRateTypeKey,
            'description' => $this->description,
        ];
    }
}
