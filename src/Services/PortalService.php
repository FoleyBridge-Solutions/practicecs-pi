<?php

// src/Services/PortalService.php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\FileTransfer;
use FoleyBridgeSolutions\PracticeCsPI\Data\FileTransferEvent;
use FoleyBridgeSolutions\PracticeCsPI\Data\FileTransferFile;
use FoleyBridgeSolutions\PracticeCsPI\Data\PortalUser;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Portal and file transfer operations against the PracticeCS API.
 *
 * Full CRUD for portal users, file transfers, file transfer files, and events.
 */
class PortalService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new portal service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    // -----------------------------------------------------------------
    // Portal Users
    // -----------------------------------------------------------------

    /**
     * List or search portal users.
     *
     * @param  array  $filters  Associative array of query filters
     * @return array Raw API response containing data and meta keys
     *
     * @throws PracticeCsException
     */
    public function listUsers(array $filters = []): array
    {
        return $this->api->get('/api/portal-users', $filters);
    }

    /**
     * Get a single portal user.
     *
     * @param  int  $portalUserKey  The portal user's primary key
     * @return PortalUser The portal user DTO
     *
     * @throws PracticeCsException
     */
    public function findUser(int $portalUserKey): PortalUser
    {
        $response = $this->api->get("/api/portal-users/{$portalUserKey}");

        return PortalUser::fromArray($response['data']);
    }

    /**
     * Create a new portal user.
     *
     * @param  array  $data  Portal user data
     * @return PortalUser The created portal user
     *
     * @throws PracticeCsException
     */
    public function createUser(array $data): PortalUser
    {
        $response = $this->api->post('/api/portal-users', $data);

        return PortalUser::fromArray($response['data']);
    }

    /**
     * Update a portal user.
     *
     * @param  int  $portalUserKey  The portal user's primary key
     * @param  array  $data  Fields to update
     * @return PortalUser The updated portal user
     *
     * @throws PracticeCsException
     */
    public function updateUser(int $portalUserKey, array $data): PortalUser
    {
        $response = $this->api->put("/api/portal-users/{$portalUserKey}", $data);

        return PortalUser::fromArray($response['data']);
    }

    /**
     * Delete a portal user.
     *
     * @param  int  $portalUserKey  The portal user's primary key
     *
     * @throws PracticeCsException
     */
    public function deleteUser(int $portalUserKey): void
    {
        $this->api->delete("/api/portal-users/{$portalUserKey}");
    }

    // -----------------------------------------------------------------
    // File Transfers
    // -----------------------------------------------------------------

    /**
     * List or search file transfers.
     *
     * @param  array  $filters  Associative array of query filters
     * @return array Raw API response containing data and meta keys
     *
     * @throws PracticeCsException
     */
    public function listTransfers(array $filters = []): array
    {
        return $this->api->get('/api/file-transfers', $filters);
    }

    /**
     * Get a single file transfer with nested files and events.
     *
     * @param  int  $fileTransferKey  The file transfer's primary key
     * @return FileTransfer The file transfer DTO
     *
     * @throws PracticeCsException
     */
    public function findTransfer(int $fileTransferKey): FileTransfer
    {
        $response = $this->api->get("/api/file-transfers/{$fileTransferKey}");

        return FileTransfer::fromArray($response['data']);
    }

    /**
     * Create a new file transfer.
     *
     * @param  array  $data  File transfer data
     * @return FileTransfer The created file transfer
     *
     * @throws PracticeCsException
     */
    public function createTransfer(array $data): FileTransfer
    {
        $response = $this->api->post('/api/file-transfers', $data);

        return FileTransfer::fromArray($response['data']);
    }

    /**
     * Update a file transfer.
     *
     * @param  int  $fileTransferKey  The file transfer's primary key
     * @param  array  $data  Fields to update
     * @return FileTransfer The updated file transfer
     *
     * @throws PracticeCsException
     */
    public function updateTransfer(int $fileTransferKey, array $data): FileTransfer
    {
        $response = $this->api->put("/api/file-transfers/{$fileTransferKey}", $data);

        return FileTransfer::fromArray($response['data']);
    }

    /**
     * Delete a file transfer.
     *
     * @param  int  $fileTransferKey  The file transfer's primary key
     *
     * @throws PracticeCsException
     */
    public function deleteTransfer(int $fileTransferKey): void
    {
        $this->api->delete("/api/file-transfers/{$fileTransferKey}");
    }

    // -----------------------------------------------------------------
    // File Transfer Files
    // -----------------------------------------------------------------

    /**
     * List files for a file transfer.
     *
     * @param  int  $fileTransferKey  The file transfer's primary key
     * @return FileTransferFile[] Array of file transfer file DTOs
     *
     * @throws PracticeCsException
     */
    public function listFiles(int $fileTransferKey): array
    {
        $response = $this->api->get("/api/file-transfers/{$fileTransferKey}/files");

        return array_map(
            fn (array $item) => FileTransferFile::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Get a single file transfer file.
     *
     * @param  int  $fileKey  The file's primary key
     * @return FileTransferFile The file transfer file DTO
     *
     * @throws PracticeCsException
     */
    public function findFile(int $fileKey): FileTransferFile
    {
        $response = $this->api->get("/api/file-transfer-files/{$fileKey}");

        return FileTransferFile::fromArray($response['data']);
    }

    /**
     * Create a file on a file transfer.
     *
     * @param  int  $fileTransferKey  The file transfer's primary key
     * @param  array  $data  File data
     * @return FileTransferFile The created file transfer file DTO
     *
     * @throws PracticeCsException
     */
    public function createFile(int $fileTransferKey, array $data): FileTransferFile
    {
        $response = $this->api->post("/api/file-transfers/{$fileTransferKey}/files", $data);

        return FileTransferFile::fromArray($response['data']);
    }

    /**
     * Delete a file transfer file.
     *
     * @param  int  $fileKey  The file's primary key
     *
     * @throws PracticeCsException
     */
    public function deleteFile(int $fileKey): void
    {
        $this->api->delete("/api/file-transfer-files/{$fileKey}");
    }

    // -----------------------------------------------------------------
    // File Transfer Events
    // -----------------------------------------------------------------

    /**
     * List events for a file transfer.
     *
     * @param  int  $fileTransferKey  The file transfer's primary key
     * @return FileTransferEvent[] Array of file transfer event DTOs
     *
     * @throws PracticeCsException
     */
    public function listEvents(int $fileTransferKey): array
    {
        $response = $this->api->get("/api/file-transfers/{$fileTransferKey}/events");

        return array_map(
            fn (array $item) => FileTransferEvent::fromArray($item),
            $response['data'] ?? []
        );
    }

    /**
     * Create an event on a file transfer.
     *
     * @param  int  $fileTransferKey  The file transfer's primary key
     * @param  array  $data  Event data
     * @return FileTransferEvent The created file transfer event DTO
     *
     * @throws PracticeCsException
     */
    public function createEvent(int $fileTransferKey, array $data): FileTransferEvent
    {
        $response = $this->api->post("/api/file-transfers/{$fileTransferKey}/events", $data);

        return FileTransferEvent::fromArray($response['data']);
    }
}
