<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Schedule item data object returned from the PracticeCS API.
 *
 * @property-read int $scheduleItemKey
 * @property-read int $scheduleItemTypeKey
 * @property-read int $staffAssignmentMethodKey
 * @property-read int $priorityKey
 * @property-read bool $isAllDayEvent
 * @property-read string|null $targetStartDateUtc
 * @property-read float $budgetedHours
 * @property-read string $description
 * @property-read string|null $scheduleItemNote
 * @property-read string|null $targetCompleteDateExclusiveUtc
 * @property-read string $currentDueDateExclusiveUtc
 * @property-read float $postedActualHours
 * @property-read float $postedActualAmount
 * @property-read float $liveActualHours
 * @property-read float $liveActualAmount
 * @property-read int|null $activityKey
 * @property-read int|null $clientKey
 * @property-read int|null $engagementKey
 * @property-read bool $isOpen
 * @property-read string|null $createDateUtc
 * @property-read string|null $updateDateUtc
 * @property-read array[] $assignments
 */
class ScheduleItem
{
    /**
     * @param  array[]  $assignments
     */
    public function __construct(
        public readonly int $scheduleItemKey,
        public readonly int $scheduleItemTypeKey = 1,
        public readonly int $staffAssignmentMethodKey = 1,
        public readonly int $priorityKey = 999,
        public readonly bool $isAllDayEvent = false,
        public readonly ?string $targetStartDateUtc = null,
        public readonly float $budgetedHours = 0.0,
        public readonly string $description = '',
        public readonly ?string $scheduleItemNote = null,
        public readonly ?string $targetCompleteDateExclusiveUtc = null,
        public readonly string $currentDueDateExclusiveUtc = '',
        public readonly float $postedActualHours = 0.0,
        public readonly float $postedActualAmount = 0.0,
        public readonly float $liveActualHours = 0.0,
        public readonly float $liveActualAmount = 0.0,
        public readonly ?int $activityKey = null,
        public readonly ?int $clientKey = null,
        public readonly ?int $engagementKey = null,
        public readonly bool $isOpen = true,
        public readonly ?string $createDateUtc = null,
        public readonly ?string $updateDateUtc = null,
        public readonly array $assignments = [],
    ) {}

    /**
     * Create a ScheduleItem from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            scheduleItemKey: (int) $data['schedule_item_KEY'],
            scheduleItemTypeKey: (int) ($data['schedule_item_type_KEY'] ?? 1),
            staffAssignmentMethodKey: (int) ($data['staff_assignment_method_KEY'] ?? 1),
            priorityKey: (int) ($data['priority_KEY'] ?? 999),
            isAllDayEvent: (bool) ($data['is_all_day_event'] ?? false),
            targetStartDateUtc: $data['target_start_date_utc'] ?? null,
            budgetedHours: (float) ($data['budgeted_hours'] ?? 0),
            description: $data['description'] ?? '',
            scheduleItemNote: $data['schedule_item_note'] ?? null,
            targetCompleteDateExclusiveUtc: $data['target_complete_date_exclusive_utc'] ?? null,
            currentDueDateExclusiveUtc: $data['current_due_date_exclusive_utc'] ?? '',
            postedActualHours: (float) ($data['posted_actual_hours'] ?? 0),
            postedActualAmount: (float) ($data['posted_actual_amount'] ?? 0),
            liveActualHours: (float) ($data['live_actual_hours'] ?? 0),
            liveActualAmount: (float) ($data['live_actual_amount'] ?? 0),
            activityKey: isset($data['activity_KEY']) ? (int) $data['activity_KEY'] : null,
            clientKey: isset($data['client_KEY']) ? (int) $data['client_KEY'] : null,
            engagementKey: isset($data['engagement_KEY']) ? (int) $data['engagement_KEY'] : null,
            isOpen: (bool) ($data['is_open'] ?? true),
            createDateUtc: $data['create_date_utc'] ?? null,
            updateDateUtc: $data['update_date_utc'] ?? null,
            assignments: $data['assignments'] ?? [],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'schedule_item_KEY' => $this->scheduleItemKey,
            'schedule_item_type_KEY' => $this->scheduleItemTypeKey,
            'staff_assignment_method_KEY' => $this->staffAssignmentMethodKey,
            'priority_KEY' => $this->priorityKey,
            'is_all_day_event' => $this->isAllDayEvent,
            'target_start_date_utc' => $this->targetStartDateUtc,
            'budgeted_hours' => $this->budgetedHours,
            'description' => $this->description,
            'schedule_item_note' => $this->scheduleItemNote,
            'target_complete_date_exclusive_utc' => $this->targetCompleteDateExclusiveUtc,
            'current_due_date_exclusive_utc' => $this->currentDueDateExclusiveUtc,
            'posted_actual_hours' => $this->postedActualHours,
            'posted_actual_amount' => $this->postedActualAmount,
            'live_actual_hours' => $this->liveActualHours,
            'live_actual_amount' => $this->liveActualAmount,
            'activity_KEY' => $this->activityKey,
            'client_KEY' => $this->clientKey,
            'engagement_KEY' => $this->engagementKey,
            'is_open' => $this->isOpen,
            'create_date_utc' => $this->createDateUtc,
            'update_date_utc' => $this->updateDateUtc,
            'assignments' => $this->assignments,
        ];
    }
}
