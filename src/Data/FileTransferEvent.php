<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * File transfer event data object returned from the PracticeCS API.
 *
 * @property-read int $fileTransferEventKey
 * @property-read int $fileTransferKey
 * @property-read int|null $fileTransferFileKey
 * @property-read int $fileTransferEventTypeKey
 * @property-read string $eventDateUtc
 * @property-read int $portalUserKey
 * @property-read string|null $label
 * @property-read string $ipAddress
 * @property-read int|null $fileSize
 * @property-read int $portalEventId
 */
class FileTransferEvent
{
    public function __construct(
        public readonly int $fileTransferEventKey,
        public readonly int $fileTransferKey = 0,
        public readonly ?int $fileTransferFileKey = null,
        public readonly int $fileTransferEventTypeKey = 0,
        public readonly string $eventDateUtc = '',
        public readonly int $portalUserKey = 0,
        public readonly ?string $label = null,
        public readonly string $ipAddress = '',
        public readonly ?int $fileSize = null,
        public readonly int $portalEventId = 0,
    ) {}

    /**
     * Create a FileTransferEvent from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            fileTransferEventKey: (int) $data['file_transfer_event_KEY'],
            fileTransferKey: (int) ($data['file_transfer_KEY'] ?? 0),
            fileTransferFileKey: isset($data['file_transfer_file_KEY']) ? (int) $data['file_transfer_file_KEY'] : null,
            fileTransferEventTypeKey: (int) ($data['file_transfer_event_type_KEY'] ?? 0),
            eventDateUtc: (string) ($data['event_date_utc'] ?? ''),
            portalUserKey: (int) ($data['portal_user_KEY'] ?? 0),
            label: $data['label'] ?? null,
            ipAddress: (string) ($data['ip_address'] ?? ''),
            fileSize: isset($data['file_size']) ? (int) $data['file_size'] : null,
            portalEventId: (int) ($data['portal_event_id'] ?? 0),
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'file_transfer_event_KEY' => $this->fileTransferEventKey,
            'file_transfer_KEY' => $this->fileTransferKey,
            'file_transfer_file_KEY' => $this->fileTransferFileKey,
            'file_transfer_event_type_KEY' => $this->fileTransferEventTypeKey,
            'event_date_utc' => $this->eventDateUtc,
            'portal_user_KEY' => $this->portalUserKey,
            'label' => $this->label,
            'ip_address' => $this->ipAddress,
            'file_size' => $this->fileSize,
            'portal_event_id' => $this->portalEventId,
        ];
    }
}
