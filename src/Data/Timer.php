<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Timer data object returned from the PracticeCS API.
 *
 * @property-read int $timerKey
 * @property-read int $sheetEntryKey
 * @property-read string $timerStartUtc
 * @property-read int $elapsedSeconds
 * @property-read string $userName
 * @property-read string|null $integrationApplicationName
 * @property-read int $roundingMethodKey
 * @property-read float $roundingIncrement
 * @property-read bool $accumulateTimeBeforeRounding
 */
class Timer
{
    public function __construct(
        public readonly int $timerKey,
        public readonly int $sheetEntryKey,
        public readonly string $timerStartUtc,
        public readonly int $elapsedSeconds = 0,
        public readonly string $userName = '',
        public readonly ?string $integrationApplicationName = null,
        public readonly int $roundingMethodKey = 1,
        public readonly float $roundingIncrement = 0.0,
        public readonly bool $accumulateTimeBeforeRounding = false,
    ) {}

    /**
     * Create a Timer from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            timerKey: (int) $data['timer_KEY'],
            sheetEntryKey: (int) $data['sheet_entry_KEY'],
            timerStartUtc: $data['timer_start_utc'] ?? '',
            elapsedSeconds: (int) ($data['elapsed_seconds'] ?? 0),
            userName: $data['user_name'] ?? '',
            integrationApplicationName: $data['integration_application_name'] ?? null,
            roundingMethodKey: (int) ($data['rounding_method_KEY'] ?? 1),
            roundingIncrement: (float) ($data['rounding_increment'] ?? 0),
            accumulateTimeBeforeRounding: (bool) ($data['accumulate_time_before_rounding'] ?? false),
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'timer_KEY' => $this->timerKey,
            'sheet_entry_KEY' => $this->sheetEntryKey,
            'timer_start_utc' => $this->timerStartUtc,
            'elapsed_seconds' => $this->elapsedSeconds,
            'user_name' => $this->userName,
            'integration_application_name' => $this->integrationApplicationName,
            'rounding_method_KEY' => $this->roundingMethodKey,
            'rounding_increment' => $this->roundingIncrement,
            'accumulate_time_before_rounding' => $this->accumulateTimeBeforeRounding,
        ];
    }
}
