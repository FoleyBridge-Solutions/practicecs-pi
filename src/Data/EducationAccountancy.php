<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Education accountancy lookup data from the PracticeCS API.
 *
 * @property-read int $educationAccountancyKey
 * @property-read string $description
 */
class EducationAccountancy
{
    public function __construct(
        public readonly int $educationAccountancyKey,
        public readonly string $description,
    ) {}

    /**
     * Create an EducationAccountancy from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            educationAccountancyKey: (int) $data['education_accountancy_KEY'],
            description: $data['description'],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'education_accountancy_KEY' => $this->educationAccountancyKey,
            'description' => $this->description,
        ];
    }
}
