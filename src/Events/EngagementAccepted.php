<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Events;

use FoleyBridgeSolutions\PracticeCsPI\Data\AcceptanceResult;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Dispatched when an engagement is successfully accepted in PracticeCS.
 */
class EngagementAccepted
{
    use Dispatchable;

    public function __construct(
        public readonly int $engagementKey,
        public readonly string $targetType,
        public readonly AcceptanceResult $result,
    ) {}
}
