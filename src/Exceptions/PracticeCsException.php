<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Exceptions;

use RuntimeException;

/**
 * Base exception for all PracticeCS API errors.
 */
class PracticeCsException extends RuntimeException
{
    /**
     * The HTTP status code from the API response, if available.
     */
    protected ?int $statusCode;

    /**
     * The response body from the API, if available.
     */
    protected ?array $responseBody;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
        ?int $statusCode = null,
        ?array $responseBody = null,
    ) {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
    }

    /**
     * Get the HTTP status code from the API response.
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * Get the response body from the API.
     */
    public function getResponseBody(): ?array
    {
        return $this->responseBody;
    }
}
