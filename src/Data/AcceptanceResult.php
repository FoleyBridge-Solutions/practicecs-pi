<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Result of an engagement acceptance operation.
 *
 * @property-read bool $success
 * @property-read int|null $newTypeKey
 * @property-read int|null $changesetKey
 * @property-read string|null $message
 * @property-read string|null $error
 */
class AcceptanceResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?int $newTypeKey = null,
        public readonly ?int $changesetKey = null,
        public readonly ?string $message = null,
        public readonly ?string $error = null,
    ) {}

    /**
     * Create an AcceptanceResult from an API response array.
     *
     * @param array $data API response data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            success: (bool) $data['success'],
            newTypeKey: isset($data['new_type_KEY']) ? (int) $data['new_type_KEY'] : null,
            changesetKey: isset($data['changeset_KEY']) ? (int) $data['changeset_KEY'] : null,
            message: $data['message'] ?? null,
            error: $data['error'] ?? null,
        );
    }

    /**
     * Convert to array representation.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'new_type_KEY' => $this->newTypeKey,
            'changeset_KEY' => $this->changesetKey,
            'message' => $this->message,
            'error' => $this->error,
        ];
    }
}
