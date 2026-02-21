<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\Contact;
use FoleyBridgeSolutions\PracticeCsPI\Data\ContactAddress;
use FoleyBridgeSolutions\PracticeCsPI\Data\ContactEmail;
use FoleyBridgeSolutions\PracticeCsPI\Data\ContactPhone;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Contact operations against the PracticeCS API.
 *
 * Full CRUD for contacts, addresses, emails, phones, and categories.
 */
class ContactService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new contact service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Contacts
    // -----------------------------------------------------------------

    /**
     * List or search contacts.
     *
     * @param  string|null  $search  Search term for name/company/file_as
     * @param  int|null  $contactTypeKey  Filter by contact type
     * @param  int  $limit  Maximum results to return
     * @param  int  $offset  Number of results to skip
     * @return array{data: Contact[], meta: array{total: int, limit: int, offset: int}}
     *
     * @throws PracticeCsException
     */
    public function list(?string $search = null, ?int $contactTypeKey = null, int $limit = 50, int $offset = 0): array
    {
        $query = ['limit' => $limit, 'offset' => $offset];

        if ($search !== null) {
            $query['search'] = $search;
        }

        if ($contactTypeKey !== null) {
            $query['contact_type_KEY'] = $contactTypeKey;
        }

        $response = $this->api->get('/api/contacts', $query);

        return [
            'data' => array_map(
                fn (array $item) => Contact::fromArray($item),
                $response['data'] ?? []
            ),
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Get a single contact with all related data.
     *
     * @param  int  $contactKey  The contact's primary key
     * @return Contact|null Contact DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function find(int $contactKey): ?Contact
    {
        try {
            $response = $this->api->get("/api/contacts/{$contactKey}");

            if (empty($response['data'])) {
                return null;
            }

            return Contact::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Get the contact record linked to a client.
     *
     * @param  string  $clientId  The client's external identifier
     * @return Contact|null Contact DTO or null if not found
     *
     * @throws PracticeCsException
     */
    public function findByClient(string $clientId): ?Contact
    {
        try {
            $response = $this->api->get("/api/contacts/by-client/{$clientId}");

            if (empty($response['data'])) {
                return null;
            }

            return Contact::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            if ($e->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * Create a new contact.
     *
     * @param  array  $data  Contact data (name, company, title, salutation, url, file_as, contact_type_KEY, preferred_locale)
     * @return Contact The created contact
     *
     * @throws PracticeCsException
     */
    public function create(array $data): Contact
    {
        $response = $this->api->post('/api/contacts', $data);

        return Contact::fromArray($response['data']);
    }

    /**
     * Update an existing contact.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  array  $data  Fields to update
     * @return Contact The updated contact
     *
     * @throws PracticeCsException
     */
    public function update(int $contactKey, array $data): Contact
    {
        $response = $this->api->put("/api/contacts/{$contactKey}", $data);

        return Contact::fromArray($response['data']);
    }

    /**
     * Delete a contact and all related records.
     *
     * @param  int  $contactKey  The contact's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function delete(int $contactKey): bool
    {
        $response = $this->api->delete("/api/contacts/{$contactKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Addresses
    // -----------------------------------------------------------------

    /**
     * List addresses for a contact.
     *
     * @param  int  $contactKey  The contact's primary key
     * @return ContactAddress[] Array of address DTOs
     *
     * @throws PracticeCsException
     */
    public function listAddresses(int $contactKey): array
    {
        $response = $this->api->get("/api/contacts/{$contactKey}/addresses");

        return array_map(
            fn (array $item) => ContactAddress::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Add an address to a contact.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  array  $data  Address data
     * @return ContactAddress The created address
     *
     * @throws PracticeCsException
     */
    public function createAddress(int $contactKey, array $data): ContactAddress
    {
        $response = $this->api->post("/api/contacts/{$contactKey}/addresses", $data);

        return ContactAddress::fromArray($response['data']);
    }

    /**
     * Update a contact address.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  int  $addressKey  The address's primary key
     * @param  array  $data  Fields to update
     * @return ContactAddress The updated address
     *
     * @throws PracticeCsException
     */
    public function updateAddress(int $contactKey, int $addressKey, array $data): ContactAddress
    {
        $response = $this->api->put("/api/contacts/{$contactKey}/addresses/{$addressKey}", $data);

        return ContactAddress::fromArray($response['data']);
    }

    /**
     * Delete a contact address.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  int  $addressKey  The address's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deleteAddress(int $contactKey, int $addressKey): bool
    {
        $response = $this->api->delete("/api/contacts/{$contactKey}/addresses/{$addressKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Emails
    // -----------------------------------------------------------------

    /**
     * List emails for a contact.
     *
     * @param  int  $contactKey  The contact's primary key
     * @return ContactEmail[] Array of email DTOs
     *
     * @throws PracticeCsException
     */
    public function listEmails(int $contactKey): array
    {
        $response = $this->api->get("/api/contacts/{$contactKey}/emails");

        return array_map(
            fn (array $item) => ContactEmail::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Add an email to a contact.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  array  $data  Email data (email, contact_email_type_KEY, display_as)
     * @return ContactEmail The created email
     *
     * @throws PracticeCsException
     */
    public function createEmail(int $contactKey, array $data): ContactEmail
    {
        $response = $this->api->post("/api/contacts/{$contactKey}/emails", $data);

        return ContactEmail::fromArray($response['data']);
    }

    /**
     * Update a contact email.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  int  $emailKey  The email's primary key
     * @param  array  $data  Fields to update
     * @return ContactEmail The updated email
     *
     * @throws PracticeCsException
     */
    public function updateEmail(int $contactKey, int $emailKey, array $data): ContactEmail
    {
        $response = $this->api->put("/api/contacts/{$contactKey}/emails/{$emailKey}", $data);

        return ContactEmail::fromArray($response['data']);
    }

    /**
     * Delete a contact email.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  int  $emailKey  The email's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deleteEmail(int $contactKey, int $emailKey): bool
    {
        $response = $this->api->delete("/api/contacts/{$contactKey}/emails/{$emailKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Phones
    // -----------------------------------------------------------------

    /**
     * List phones for a contact.
     *
     * @param  int  $contactKey  The contact's primary key
     * @return ContactPhone[] Array of phone DTOs
     *
     * @throws PracticeCsException
     */
    public function listPhones(int $contactKey): array
    {
        $response = $this->api->get("/api/contacts/{$contactKey}/phones");

        return array_map(
            fn (array $item) => ContactPhone::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Add a phone to a contact.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  array  $data  Phone data (phone, contact_phone_type_KEY, extension)
     * @return ContactPhone The created phone
     *
     * @throws PracticeCsException
     */
    public function createPhone(int $contactKey, array $data): ContactPhone
    {
        $response = $this->api->post("/api/contacts/{$contactKey}/phones", $data);

        return ContactPhone::fromArray($response['data']);
    }

    /**
     * Update a contact phone.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  int  $phoneKey  The phone's primary key
     * @param  array  $data  Fields to update
     * @return ContactPhone The updated phone
     *
     * @throws PracticeCsException
     */
    public function updatePhone(int $contactKey, int $phoneKey, array $data): ContactPhone
    {
        $response = $this->api->put("/api/contacts/{$contactKey}/phones/{$phoneKey}", $data);

        return ContactPhone::fromArray($response['data']);
    }

    /**
     * Delete a contact phone.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  int  $phoneKey  The phone's primary key
     * @return bool True if deleted
     *
     * @throws PracticeCsException
     */
    public function deletePhone(int $contactKey, int $phoneKey): bool
    {
        $response = $this->api->delete("/api/contacts/{$contactKey}/phones/{$phoneKey}");

        return $response['data']['deleted'] ?? false;
    }

    // -----------------------------------------------------------------
    // Categories
    // -----------------------------------------------------------------

    /**
     * List all contact categories.
     *
     * @return array[] Array of category arrays
     *
     * @throws PracticeCsException
     */
    public function listCategories(): array
    {
        $response = $this->api->get('/api/contacts/categories');

        return $response['data'] ?? [];
    }

    /**
     * Get category assignments for a contact.
     *
     * @param  int  $contactKey  The contact's primary key
     * @return array[] Array of category assignment arrays
     *
     * @throws PracticeCsException
     */
    public function listContactCategories(int $contactKey): array
    {
        $response = $this->api->get("/api/contacts/{$contactKey}/categories");

        return $response['data'] ?? [];
    }

    /**
     * Assign a category to a contact.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  int  $categoryKey  The category's primary key
     * @return int The contact_contact_category_KEY of the new assignment
     *
     * @throws PracticeCsException
     */
    public function assignCategory(int $contactKey, int $categoryKey): int
    {
        $response = $this->api->post("/api/contacts/{$contactKey}/categories", [
            'contact_category_KEY' => $categoryKey,
        ]);

        return (int) $response['data']['contact_contact_category_KEY'];
    }

    /**
     * Remove a category assignment from a contact.
     *
     * @param  int  $contactKey  The contact's primary key
     * @param  int  $categoryKey  The category's primary key
     * @return bool True if removed
     *
     * @throws PracticeCsException
     */
    public function removeCategory(int $contactKey, int $categoryKey): bool
    {
        $response = $this->api->delete("/api/contacts/{$contactKey}/categories/{$categoryKey}");

        return $response['data']['deleted'] ?? false;
    }
}
