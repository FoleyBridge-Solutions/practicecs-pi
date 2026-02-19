<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Exceptions;

/**
 * Thrown when a client cannot be found in PracticeCS.
 */
class ClientNotFoundException extends PracticeCsException
{
    /**
     * Create a new exception for a missing client by key.
     */
    public static function forKey(int $clientKey): self
    {
        return new self("Client not found with key: {$clientKey}", 0, null, 404);
    }

    /**
     * Create a new exception for a missing client by ID.
     */
    public static function forId(string $clientId): self
    {
        return new self("Client not found with ID: {$clientId}", 0, null, 404);
    }
}
