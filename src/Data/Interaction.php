<?php

declare(strict_types=1);

// src/Data/Interaction.php

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Interaction (CRM) data object returned from the PracticeCS API.
 *
 * @property-read int $interactionKey
 * @property-read int $interactionSubtypeKey
 * @property-read string $entryDateUtc
 * @property-read string $subject
 * @property-read string $body
 * @property-read int $priorityKey
 * @property-read bool $cleared
 * @property-read int|null $integrationApplicationKey
 * @property-read int|null $fileTransferEventKey
 * @property-read array $contacts
 * @property-read array|null $phone
 * @property-read array $emails
 * @property-read array $links
 */
class Interaction
{
    public function __construct(
        public readonly int $interactionKey,
        public readonly int $interactionSubtypeKey,
        public readonly string $entryDateUtc,
        public readonly string $subject,
        public readonly string $body,
        public readonly int $priorityKey,
        public readonly bool $cleared,
        public readonly ?int $integrationApplicationKey = null,
        public readonly ?int $fileTransferEventKey = null,
        public readonly array $contacts = [],
        public readonly ?array $phone = null,
        public readonly array $emails = [],
        public readonly array $links = [],
    ) {}

    /**
     * Create an Interaction from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            interactionKey: (int) $data['interaction_KEY'],
            interactionSubtypeKey: (int) $data['interaction_subtype_KEY'],
            entryDateUtc: (string) $data['entry_date_utc'],
            subject: (string) $data['subject'],
            body: (string) $data['body'],
            priorityKey: (int) $data['priority_KEY'],
            cleared: (bool) ($data['cleared'] ?? false),
            integrationApplicationKey: isset($data['integration_application_KEY']) ? (int) $data['integration_application_KEY'] : null,
            fileTransferEventKey: isset($data['file_transfer_event_KEY']) ? (int) $data['file_transfer_event_KEY'] : null,
            contacts: $data['contacts'] ?? [],
            phone: $data['phone'] ?? null,
            emails: $data['emails'] ?? [],
            links: $data['links'] ?? [],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'interaction_KEY' => $this->interactionKey,
            'interaction_subtype_KEY' => $this->interactionSubtypeKey,
            'entry_date_utc' => $this->entryDateUtc,
            'subject' => $this->subject,
            'body' => $this->body,
            'priority_KEY' => $this->priorityKey,
            'cleared' => $this->cleared,
            'integration_application_KEY' => $this->integrationApplicationKey,
            'file_transfer_event_KEY' => $this->fileTransferEventKey,
            'contacts' => $this->contacts,
            'phone' => $this->phone,
            'emails' => $this->emails,
            'links' => $this->links,
        ];
    }
}
