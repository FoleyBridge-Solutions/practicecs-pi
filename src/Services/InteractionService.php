<?php

// src/Services/InteractionService.php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Interaction;
use FoleyBridgeSolutions\PracticeCsPI\Data\InteractionSubtype;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Interaction (CRM) operations against the PracticeCS API.
 *
 * Full CRUD for interactions, contacts, subtypes, associations, phone, and emails.
 */
class InteractionService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new interaction service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Interactions
    // -----------------------------------------------------------------

    /**
     * List or search interactions.
     *
     * @param  array  $filters  Associative array of query filters
     * @return array{data: Interaction[], meta: array} Raw response with data and meta
     *
     * @throws PracticeCsException
     */
    public function list(array $filters = []): array
    {
        $response = $this->api->get('/api/interactions', $filters);

        return [
            'data' => array_map(
                fn (array $item) => Interaction::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single interaction by its primary key.
     *
     * @param  int  $interactionKey  The interaction's primary key
     * @return Interaction The Interaction DTO
     *
     * @throws PracticeCsException
     */
    public function find(int $interactionKey): Interaction
    {
        $response = $this->api->get("/api/interactions/{$interactionKey}");

        return Interaction::fromArray($response['data']);
    }

    /**
     * Create a new interaction.
     *
     * @param  array  $data  Interaction data
     * @return Interaction The created Interaction DTO
     *
     * @throws PracticeCsException
     */
    public function create(array $data): Interaction
    {
        $response = $this->api->post('/api/interactions', $data);

        return Interaction::fromArray($response['data']);
    }

    /**
     * Update an existing interaction.
     *
     * @param  int  $interactionKey  The interaction's primary key
     * @param  array  $data  Fields to update
     * @return Interaction The updated Interaction DTO
     *
     * @throws PracticeCsException
     */
    public function update(int $interactionKey, array $data): Interaction
    {
        $response = $this->api->put("/api/interactions/{$interactionKey}", $data);

        return Interaction::fromArray($response['data']);
    }

    /**
     * Delete an interaction.
     *
     * @param  int  $interactionKey  The interaction's primary key
     *
     * @throws PracticeCsException
     */
    public function delete(int $interactionKey): void
    {
        $this->api->delete("/api/interactions/{$interactionKey}");
    }

    // -----------------------------------------------------------------
    // Interaction Contacts
    // -----------------------------------------------------------------

    /**
     * List contacts associated with an interaction.
     *
     * @param  int  $interactionKey  The interaction's primary key
     * @return array[] Array of contact associative arrays
     *
     * @throws PracticeCsException
     */
    public function listContacts(int $interactionKey): array
    {
        $response = $this->api->get("/api/interactions/{$interactionKey}/contacts");

        return $response['data'] ?? [];
    }

    /**
     * Add a contact to an interaction.
     *
     * @param  int  $interactionKey  The interaction's primary key
     * @param  array  $data  Contact association data
     * @return array The response data
     *
     * @throws PracticeCsException
     */
    public function addContact(int $interactionKey, array $data): array
    {
        $response = $this->api->post("/api/interactions/{$interactionKey}/contacts", $data);

        return $response['data'] ?? [];
    }

    /**
     * Remove a contact from an interaction.
     *
     * @param  int  $interactionKey  The interaction's primary key
     * @param  int  $contactKey  The contact's primary key
     *
     * @throws PracticeCsException
     */
    public function removeContact(int $interactionKey, int $contactKey): void
    {
        $this->api->delete("/api/interactions/{$interactionKey}/contacts/{$contactKey}");
    }

    // -----------------------------------------------------------------
    // Subtypes
    // -----------------------------------------------------------------

    /**
     * List interaction subtypes.
     *
     * @param  array  $filters  Associative array of query filters
     * @return InteractionSubtype[] Array of InteractionSubtype DTOs
     *
     * @throws PracticeCsException
     */
    public function listSubtypes(array $filters = []): array
    {
        $response = $this->api->get('/api/interaction-subtypes', $filters);

        return array_map(
            fn (array $item) => InteractionSubtype::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get a single interaction subtype by its primary key.
     *
     * @param  int  $subtypeKey  The subtype's primary key
     * @return InteractionSubtype The InteractionSubtype DTO
     *
     * @throws PracticeCsException
     */
    public function findSubtype(int $subtypeKey): InteractionSubtype
    {
        $response = $this->api->get("/api/interaction-subtypes/{$subtypeKey}");

        return InteractionSubtype::fromArray($response['data']);
    }

    // -----------------------------------------------------------------
    // Associations
    // -----------------------------------------------------------------

    /**
     * List all interaction associations.
     *
     * @return array[] Array of association associative arrays
     *
     * @throws PracticeCsException
     */
    public function listAssociations(): array
    {
        $response = $this->api->get('/api/interaction-associations');

        return $response['data'] ?? [];
    }

    // -----------------------------------------------------------------
    // Phone
    // -----------------------------------------------------------------

    /**
     * Get the phone record for an interaction.
     *
     * @param  int  $interactionKey  The interaction's primary key
     * @return array|null Phone data as associative array, or null if none
     *
     * @throws PracticeCsException
     */
    public function getPhone(int $interactionKey): ?array
    {
        try {
            $response = $this->api->get("/api/interactions/{$interactionKey}/phone");

            return $response['data'] ?? null;
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Set or update the phone record for an interaction.
     *
     * @param  int  $interactionKey  The interaction's primary key
     * @param  array  $data  Phone data
     * @return array The response data
     *
     * @throws PracticeCsException
     */
    public function setPhone(int $interactionKey, array $data): array
    {
        $response = $this->api->put("/api/interactions/{$interactionKey}/phone", $data);

        return $response['data'] ?? [];
    }

    /**
     * Delete the phone record for an interaction.
     *
     * @param  int  $interactionKey  The interaction's primary key
     *
     * @throws PracticeCsException
     */
    public function deletePhone(int $interactionKey): void
    {
        $this->api->delete("/api/interactions/{$interactionKey}/phone");
    }

    // -----------------------------------------------------------------
    // Emails
    // -----------------------------------------------------------------

    /**
     * List email addresses associated with an interaction.
     *
     * @param  int  $interactionKey  The interaction's primary key
     * @return array[] Array of email associative arrays
     *
     * @throws PracticeCsException
     */
    public function listEmails(int $interactionKey): array
    {
        $response = $this->api->get("/api/interactions/{$interactionKey}/emails");

        return $response['data'] ?? [];
    }

    /**
     * Add an email address to an interaction.
     *
     * @param  int  $interactionKey  The interaction's primary key
     * @param  array  $data  Email data
     * @return array The response data
     *
     * @throws PracticeCsException
     */
    public function addEmail(int $interactionKey, array $data): array
    {
        $response = $this->api->post("/api/interactions/{$interactionKey}/emails", $data);

        return $response['data'] ?? [];
    }

    /**
     * Remove an email address from an interaction.
     *
     * @param  int  $interactionKey  The interaction's primary key
     * @param  int  $emailAddressKey  The email address's primary key
     *
     * @throws PracticeCsException
     */
    public function removeEmail(int $interactionKey, int $emailAddressKey): void
    {
        $this->api->delete("/api/interactions/{$interactionKey}/emails/{$emailAddressKey}");
    }
}
