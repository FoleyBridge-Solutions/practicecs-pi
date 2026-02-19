<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * Dispatched when a payment write to PracticeCS fails.
 */
class PaymentWriteFailed
{
    use Dispatchable;

    public function __construct(
        public readonly int $clientKey,
        public readonly float $amount,
        public readonly string $paymentMethod,
        public readonly string $error,
    ) {}
}
