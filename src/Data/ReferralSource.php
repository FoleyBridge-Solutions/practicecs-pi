<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Referral source lookup data from the PracticeCS API.
 *
 * @property-read int $referralSourceKey
 * @property-read string $description
 */
class ReferralSource
{
    public function __construct(
        public readonly int $referralSourceKey,
        public readonly string $description,
    ) {}

    /**
     * Create a ReferralSource from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            referralSourceKey: (int) $data['referral_source_KEY'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'referral_source_KEY' => $this->referralSourceKey,
            'description' => $this->description,
        ];
    }
}
