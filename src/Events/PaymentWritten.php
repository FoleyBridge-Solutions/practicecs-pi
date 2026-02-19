<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Events;

use FoleyBridgeSolutions\PracticeCsPI\Data\LedgerEntry;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Dispatched when a payment is successfully written to PracticeCS.
 */
class PaymentWritten
{
    use Dispatchable;

    public function __construct(
        public readonly int $clientKey,
        public readonly float $amount,
        public readonly string $paymentMethod,
        public readonly LedgerEntry $ledgerEntry,
    ) {}
}
