<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Task data object returned from the PracticeCS API.
 *
 * @property-read int $taskKey
 * @property-read int $projectKey
 * @property-read int $completionOrder
 * @property-read float $budgetedAmount
 * @property-read string|null $actualStartDate
 * @property-read string|null $actualCompleteDate
 * @property-read string|null $target
 * @property-read string|null $completionEvent
 * @property-read int $taskNumber
 * @property-read int $projectTemplateKey
 * @property-read int $scheduleItemKey
 * @property-read bool $budgetedAmountIsCalculated
 * @property-read int|null $taskTemplateKey
 * @property-read array|null $scheduleItem
 */
class Task
{
    public function __construct(
        public readonly int $taskKey,
        public readonly int $projectKey,
        public readonly int $completionOrder = 1,
        public readonly float $budgetedAmount = 0.0,
        public readonly ?string $actualStartDate = null,
        public readonly ?string $actualCompleteDate = null,
        public readonly ?string $target = null,
        public readonly ?string $completionEvent = null,
        public readonly int $taskNumber = 0,
        public readonly int $projectTemplateKey = 0,
        public readonly int $scheduleItemKey = 0,
        public readonly bool $budgetedAmountIsCalculated = true,
        public readonly ?int $taskTemplateKey = null,
        public readonly ?array $scheduleItem = null,
    ) {}

    /**
     * Create a Task from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            taskKey: (int) $data['task_KEY'],
            projectKey: (int) $data['project_KEY'],
            completionOrder: (int) ($data['completion_order'] ?? 1),
            budgetedAmount: (float) ($data['budgeted_amount'] ?? 0),
            actualStartDate: $data['actual_start_date'] ?? null,
            actualCompleteDate: $data['actual_complete_date'] ?? null,
            target: $data['target'] ?? null,
            completionEvent: $data['completion_event'] ?? null,
            taskNumber: (int) ($data['task_number'] ?? 0),
            projectTemplateKey: (int) ($data['project_template_KEY'] ?? 0),
            scheduleItemKey: (int) ($data['schedule_item_KEY'] ?? 0),
            budgetedAmountIsCalculated: (bool) ($data['budgeted_amount_is_calculated'] ?? true),
            taskTemplateKey: isset($data['task_template_KEY']) ? (int) $data['task_template_KEY'] : null,
            scheduleItem: $data['schedule_item'] ?? null,
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'task_KEY' => $this->taskKey,
            'project_KEY' => $this->projectKey,
            'completion_order' => $this->completionOrder,
            'budgeted_amount' => $this->budgetedAmount,
            'actual_start_date' => $this->actualStartDate,
            'actual_complete_date' => $this->actualCompleteDate,
            'target' => $this->target,
            'completion_event' => $this->completionEvent,
            'task_number' => $this->taskNumber,
            'project_template_KEY' => $this->projectTemplateKey,
            'schedule_item_KEY' => $this->scheduleItemKey,
            'budgeted_amount_is_calculated' => $this->budgetedAmountIsCalculated,
            'task_template_KEY' => $this->taskTemplateKey,
            'schedule_item' => $this->scheduleItem,
        ];
    }
}
