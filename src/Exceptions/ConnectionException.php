<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Exceptions;

/**
 * Thrown when the PracticeCS API is unreachable or returns a connection-level error.
 */
class ConnectionException extends PracticeCsException
{
    /**
     * Create a new exception for a connection timeout.
     *
     * Use when the PracticeCS API does not respond within the configured timeout period.
     *
     * @param  string  $url  The API endpoint URL that timed out.
     */
    public static function timeout(string $url): self
    {
        return new self("Connection to PracticeCS API timed out: {$url}");
    }

    /**
     * Create a new exception for a refused connection.
     *
     * Use when the PracticeCS API actively refuses the TCP connection
     * (e.g. the service is down or the port is not listening).
     *
     * @param  string  $url  The API endpoint URL that refused the connection.
     */
    public static function refused(string $url): self
    {
        return new self("Connection to PracticeCS API refused: {$url}");
    }

    /**
     * Create a new exception for a general connection failure.
     */
    public static function failed(string $url, string $reason): self
    {
        return new self("Failed to connect to PracticeCS API at {$url}: {$reason}");
    }
}
