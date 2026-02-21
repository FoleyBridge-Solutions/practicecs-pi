<?php

// src/Data/FileTransfer.php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * File transfer data object returned from the PracticeCS API.
 *
 * @property-read int $fileTransferKey
 * @property-read int $fileTransferTypeKey
 * @property-read int $fileTransferStatusKey
 * @property-read int $staffPortalUserKey
 * @property-read int $staffContactKey
 * @property-read int $contactPortalUserKey
 * @property-read int $contactContactKey
 * @property-read int|null $ofContactKey
 * @property-read string $subject
 * @property-read string $body
 * @property-read int $fileCount
 * @property-read int $totalFileSize
 * @property-read string $expirationDateUtc
 * @property-read string $fileTransferCreateDateUtc
 * @property-read string|null $completeDateUtc
 * @property-read array $files
 * @property-read array $events
 */
class FileTransfer
{
    public function __construct(
        public readonly int $fileTransferKey,
        public readonly int $fileTransferTypeKey = 0,
        public readonly int $fileTransferStatusKey = 0,
        public readonly int $staffPortalUserKey = 0,
        public readonly int $staffContactKey = 0,
        public readonly int $contactPortalUserKey = 0,
        public readonly int $contactContactKey = 0,
        public readonly ?int $ofContactKey = null,
        public readonly string $subject = '',
        public readonly string $body = '',
        public readonly int $fileCount = 0,
        public readonly int $totalFileSize = 0,
        public readonly string $expirationDateUtc = '',
        public readonly string $fileTransferCreateDateUtc = '',
        public readonly ?string $completeDateUtc = null,
        public readonly array $files = [],
        public readonly array $events = [],
    ) {}

    /**
     * Create a FileTransfer from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            fileTransferKey: (int) $data['file_transfer_KEY'],
            fileTransferTypeKey: (int) ($data['file_transfer_type_KEY'] ?? 0),
            fileTransferStatusKey: (int) ($data['file_transfer_status_KEY'] ?? 0),
            staffPortalUserKey: (int) ($data['staff__portal_user_KEY'] ?? 0),
            staffContactKey: (int) ($data['staff__contact_KEY'] ?? 0),
            contactPortalUserKey: (int) ($data['contact__portal_user_KEY'] ?? 0),
            contactContactKey: (int) ($data['contact__contact_KEY'] ?? 0),
            ofContactKey: isset($data['of__contact_KEY']) ? (int) $data['of__contact_KEY'] : null,
            subject: (string) ($data['subject'] ?? ''),
            body: (string) ($data['body'] ?? ''),
            fileCount: (int) ($data['file_count'] ?? 0),
            totalFileSize: (int) ($data['total_file_size'] ?? 0),
            expirationDateUtc: (string) ($data['expiration_date_utc'] ?? ''),
            fileTransferCreateDateUtc: (string) ($data['file_transfer_create_date_utc'] ?? ''),
            completeDateUtc: $data['complete_date_utc'] ?? null,
            files: $data['files'] ?? [],
            events: $data['events'] ?? [],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'file_transfer_KEY' => $this->fileTransferKey,
            'file_transfer_type_KEY' => $this->fileTransferTypeKey,
            'file_transfer_status_KEY' => $this->fileTransferStatusKey,
            'staff__portal_user_KEY' => $this->staffPortalUserKey,
            'staff__contact_KEY' => $this->staffContactKey,
            'contact__portal_user_KEY' => $this->contactPortalUserKey,
            'contact__contact_KEY' => $this->contactContactKey,
            'of__contact_KEY' => $this->ofContactKey,
            'subject' => $this->subject,
            'body' => $this->body,
            'file_count' => $this->fileCount,
            'total_file_size' => $this->totalFileSize,
            'expiration_date_utc' => $this->expirationDateUtc,
            'file_transfer_create_date_utc' => $this->fileTransferCreateDateUtc,
            'complete_date_utc' => $this->completeDateUtc,
            'files' => $this->files,
            'events' => $this->events,
        ];
    }
}
