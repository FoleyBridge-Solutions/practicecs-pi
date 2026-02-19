<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Engagement data object returned from the PracticeCS API.
 *
 * @property-read int $engagementKey
 * @property-read string $engagementName
 * @property-read string|null $engagementType
 * @property-read string|null $engagementTypeId
 * @property-read int $clientKey
 * @property-read string $clientName
 * @property-read string $clientId
 * @property-read string|null $groupName
 * @property-read float $totalBudget
 * @property-read array $projects
 */
class Engagement
{
    public function __construct(
        public readonly int $engagementKey,
        public readonly string $engagementName,
        public readonly ?string $engagementType = null,
        public readonly ?string $engagementTypeId = null,
        public readonly int $clientKey = 0,
        public readonly string $clientName = '',
        public readonly string $clientId = '',
        public readonly ?string $groupName = null,
        public readonly float $totalBudget = 0.00,
        public readonly array $projects = [],
    ) {}

    /**
     * Create an Engagement from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            engagementKey: (int) $data['engagement_KEY'],
            engagementName: $data['engagement_name'],
            engagementType: $data['engagement_type'] ?? null,
            engagementTypeId: $data['engagement_type_id'] ?? null,
            clientKey: (int) ($data['client_KEY'] ?? 0),
            clientName: $data['client_name'] ?? '',
            clientId: $data['client_id'] ?? '',
            groupName: $data['group_name'] ?? null,
            totalBudget: (float) ($data['total_budget'] ?? 0),
            projects: $data['projects'] ?? [],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'engagement_KEY' => $this->engagementKey,
            'engagement_name' => $this->engagementName,
            'engagement_type' => $this->engagementType,
            'engagement_type_id' => $this->engagementTypeId,
            'client_KEY' => $this->clientKey,
            'client_name' => $this->clientName,
            'client_id' => $this->clientId,
            'group_name' => $this->groupName,
            'total_budget' => $this->totalBudget,
            'projects' => $this->projects,
        ];
    }
}
