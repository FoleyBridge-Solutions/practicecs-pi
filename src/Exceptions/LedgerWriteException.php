<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Exceptions;

/**
 * Thrown when a ledger write operation (payment, memo, deferred payment) fails.
 */
class LedgerWriteException extends PracticeCsException
{
    /**
     * Create a new exception for a failed payment write.
     */
    public static function paymentFailed(string $reason, ?array $responseBody = null): self
    {
        return new self("Payment write failed: {$reason}", 0, null, 422, $responseBody);
    }

    /**
     * Create a new exception for a failed memo write.
     */
    public static function memoFailed(string $reason, ?array $responseBody = null): self
    {
        return new self("Memo write failed: {$reason}", 0, null, 422, $responseBody);
    }

    /**
     * Create a new exception for a failed invoice application.
     */
    public static function applicationFailed(string $reason, ?array $responseBody = null): self
    {
        return new self("Invoice application failed: {$reason}", 0, null, 422, $responseBody);
    }
}
