<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Schedule entry data object returned from the PracticeCS API.
 *
 * @property-read int $scheduleEntryKey
 * @property-read int $scheduleItemKey
 * @property-read int $staffKey
 * @property-read string $scheduleEntryStartUtc
 * @property-read int $scheduleEntryDuration
 * @property-read int $scheduleEntryScheduled
 * @property-read string $scheduleEntryEndUtc
 * @property-read int|null $calendarCategoryKey
 */
class ScheduleEntry
{
    public function __construct(
        public readonly int $scheduleEntryKey,
        public readonly int $scheduleItemKey,
        public readonly int $staffKey,
        public readonly string $scheduleEntryStartUtc = '',
        public readonly int $scheduleEntryDuration = 0,
        public readonly int $scheduleEntryScheduled = 0,
        public readonly string $scheduleEntryEndUtc = '',
        public readonly ?int $calendarCategoryKey = null,
    ) {}

    /**
     * Create a ScheduleEntry from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            scheduleEntryKey: (int) $data['schedule_entry_KEY'],
            scheduleItemKey: (int) $data['schedule_item_KEY'],
            staffKey: (int) $data['staff_KEY'],
            scheduleEntryStartUtc: $data['schedule_entry_start_utc'] ?? '',
            scheduleEntryDuration: (int) ($data['schedule_entry_duration'] ?? 0),
            scheduleEntryScheduled: (int) ($data['schedule_entry_scheduled'] ?? 0),
            scheduleEntryEndUtc: $data['schedule_entry_end_utc'] ?? '',
            calendarCategoryKey: isset($data['calendar_category_KEY']) ? (int) $data['calendar_category_KEY'] : null,
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'schedule_entry_KEY' => $this->scheduleEntryKey,
            'schedule_item_KEY' => $this->scheduleItemKey,
            'staff_KEY' => $this->staffKey,
            'schedule_entry_start_utc' => $this->scheduleEntryStartUtc,
            'schedule_entry_duration' => $this->scheduleEntryDuration,
            'schedule_entry_scheduled' => $this->scheduleEntryScheduled,
            'schedule_entry_end_utc' => $this->scheduleEntryEndUtc,
            'calendar_category_KEY' => $this->calendarCategoryKey,
        ];
    }
}
