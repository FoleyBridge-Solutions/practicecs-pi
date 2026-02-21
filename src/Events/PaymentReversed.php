<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * Dispatched when a payment is successfully reversed (deleted) from PracticeCS.
 */
class PaymentReversed
{
    use Dispatchable;

    public function __construct(
        public readonly int $ledgerEntryKey,
        public readonly int $staffKey,
        public readonly array $deletionCounts,
    ) {}
}
