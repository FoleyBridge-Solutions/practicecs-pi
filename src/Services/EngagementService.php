<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\AcceptanceResult;
use FoleyBridgeSolutions\PracticeCsPI\Data\Engagement;
use FoleyBridgeSolutions\PracticeCsPI\Events\EngagementAccepted;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Engagement operations against the PracticeCS API.
 *
 * Maps to EngagementAcceptanceService and PaymentRepository engagement
 * methods in TR-Pay.
 */
class EngagementService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new engagement service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Get pending projects (EXPANSION engagements) for a client group.
     *
     * Returns raw PracticeCS data. Filtering against local project_acceptances
     * should be done in the consuming application (TR-Pay).
     *
     * @param  int|null  $clientKey  The client_KEY
     * @param  string|null  $clientId  The client_id (alternative lookup)
     * @return Engagement[] Array of Engagement DTOs
     *
     * @throws PracticeCsException
     */
    public function getPendingProjects(?int $clientKey = null, ?string $clientId = null): array
    {
        $query = [];
        if ($clientKey !== null) {
            $query['client_KEY'] = $clientKey;
        }
        if ($clientId !== null) {
            $query['client_id'] = $clientId;
        }

        $response = $this->api->get('/api/engagements/pending-projects', $query);

        return array_map(
            fn (array $item) => Engagement::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Accept a single engagement (change type from EXPANSION to target type).
     *
     * @param  int  $engagementKey  The engagement_KEY to accept
     * @param  int  $staffKey  The staff_KEY performing the acceptance
     * @param  string|null  $projectDescription  Optional description for the project
     * @return AcceptanceResult Result of the acceptance operation
     *
     * @throws PracticeCsException
     */
    public function acceptEngagement(int $engagementKey, int $staffKey, ?string $projectDescription = null): AcceptanceResult
    {
        $data = [
            'engagement_KEY' => $engagementKey,
            'staff_KEY' => $staffKey,
        ];

        if ($projectDescription !== null) {
            $data['project_description'] = $projectDescription;
        }

        $response = $this->api->post('/api/engagements/accept', $data);

        $result = AcceptanceResult::fromArray($response['data']);

        if ($result->success) {
            EngagementAccepted::dispatch(
                $engagementKey,
                (string) ($response['data']['new_type_KEY'] ?? 'unknown'),
                $result
            );
        }

        return $result;
    }

    /**
     * Accept multiple engagements in batch.
     *
     * @param  array  $engagementKeys  Array of engagement_KEY values to accept
     * @param  int  $staffKey  The staff_KEY performing the acceptance
     * @param  array  $projectDescriptions  Optional descriptions keyed by engagement_KEY
     * @return AcceptanceResult[] Array of AcceptanceResult DTOs keyed by engagement_KEY
     *
     * @throws PracticeCsException
     */
    public function acceptEngagements(array $engagementKeys, int $staffKey, array $projectDescriptions = []): array
    {
        $response = $this->api->post('/api/engagements/accept-batch', [
            'engagement_keys' => $engagementKeys,
            'staff_KEY' => $staffKey,
            'project_descriptions' => $projectDescriptions,
        ]);

        $results = [];
        foreach ($response['data']['results'] ?? [] as $key => $item) {
            $result = AcceptanceResult::fromArray($item);
            $results[$key] = $result;

            if ($result->success) {
                EngagementAccepted::dispatch(
                    (int) $key,
                    (string) ($item['new_type_KEY'] ?? 'unknown'),
                    $result
                );
            }
        }

        return $results;
    }

    /**
     * Get the target type key for a given template ID.
     *
     * Used to determine what engagement type an EXPANSION should be changed to.
     *
     * @param  string  $templateId  The engagement template ID
     * @return int|null The target engagement_type_KEY, or null if not found
     *
     * @throws PracticeCsException
     */
    public function getTargetTypeKey(string $templateId): ?int
    {
        $response = $this->api->get('/api/engagements/target-type', [
            'template_id' => $templateId,
        ]);

        return $response['data']['engagement_type_KEY'] ?? null;
    }

    /**
     * Check if a template ID corresponds to an EXPANSION template.
     *
     * @param  string  $templateId  The engagement template ID
     * @return bool True if the template is an EXPANSION type
     *
     * @throws PracticeCsException
     */
    public function isExpansionTemplate(string $templateId): bool
    {
        $response = $this->api->get('/api/engagements/is-expansion', [
            'template_id' => $templateId,
        ]);

        return (bool) ($response['data']['is_expansion'] ?? false);
    }
}
