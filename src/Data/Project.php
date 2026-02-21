<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Project data object returned from the PracticeCS API.
 *
 * @property-read int $projectKey
 * @property-read int $scheduleItemKey
 * @property-read int $projectNumber
 * @property-read string|null $longDescription
 * @property-read int|null $responsibleStaffKey
 * @property-read int|null $departmentKey
 * @property-read string|null $target
 * @property-read string|null $completionEvent
 * @property-read bool $budgetedBasedOnTasks
 * @property-read float $budgetedAmount
 * @property-read float $percentComplete
 * @property-read string|null $receivedDate
 * @property-read string|null $actualStartDate
 * @property-read string|null $actualCompleteDate
 * @property-read int $projectTemplateKey
 * @property-read string $projectTemplateId
 * @property-read string|null $originalDueDate
 * @property-read int $extensionNumber
 * @property-read array|null $scheduleItem
 * @property-read Task[] $tasks
 * @property-read array[] $extensions
 */
class Project
{
    /**
     * @param  Task[]  $tasks
     * @param  array[]  $extensions
     */
    public function __construct(
        public readonly int $projectKey,
        public readonly int $scheduleItemKey,
        public readonly int $projectNumber = 0,
        public readonly ?string $longDescription = null,
        public readonly ?int $responsibleStaffKey = null,
        public readonly ?int $departmentKey = null,
        public readonly ?string $target = null,
        public readonly ?string $completionEvent = null,
        public readonly bool $budgetedBasedOnTasks = true,
        public readonly float $budgetedAmount = 0.0,
        public readonly float $percentComplete = 0.0,
        public readonly ?string $receivedDate = null,
        public readonly ?string $actualStartDate = null,
        public readonly ?string $actualCompleteDate = null,
        public readonly int $projectTemplateKey = 0,
        public readonly string $projectTemplateId = '',
        public readonly ?string $originalDueDate = null,
        public readonly int $extensionNumber = 0,
        public readonly ?array $scheduleItem = null,
        public readonly array $tasks = [],
        public readonly array $extensions = [],
    ) {}

    /**
     * Create a Project from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        $tasks = [];
        if (! empty($data['tasks'])) {
            $tasks = array_map(
                fn (array $t) => Task::fromArray($t),
                $data['tasks']
            );
        }

        return new self(
            projectKey: (int) $data['project_KEY'],
            scheduleItemKey: (int) $data['schedule_item_KEY'],
            projectNumber: (int) ($data['project_number'] ?? 0),
            longDescription: $data['long_description'] ?? null,
            responsibleStaffKey: isset($data['responsible__staff_KEY']) ? (int) $data['responsible__staff_KEY'] : null,
            departmentKey: isset($data['department_KEY']) ? (int) $data['department_KEY'] : null,
            target: $data['target'] ?? null,
            completionEvent: $data['completion_event'] ?? null,
            budgetedBasedOnTasks: (bool) ($data['budgeted_based_on_tasks'] ?? true),
            budgetedAmount: (float) ($data['budgeted_amount'] ?? 0),
            percentComplete: (float) ($data['percent_complete'] ?? 0),
            receivedDate: $data['received_date'] ?? null,
            actualStartDate: $data['actual_start_date'] ?? null,
            actualCompleteDate: $data['actual_complete_date'] ?? null,
            projectTemplateKey: (int) ($data['project_template_KEY'] ?? 0),
            projectTemplateId: $data['project_template_id'] ?? '',
            originalDueDate: $data['original_due_date'] ?? null,
            extensionNumber: (int) ($data['extension_number'] ?? 0),
            scheduleItem: $data['schedule_item'] ?? null,
            tasks: $tasks,
            extensions: $data['extensions'] ?? [],
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'project_KEY' => $this->projectKey,
            'schedule_item_KEY' => $this->scheduleItemKey,
            'project_number' => $this->projectNumber,
            'long_description' => $this->longDescription,
            'responsible__staff_KEY' => $this->responsibleStaffKey,
            'department_KEY' => $this->departmentKey,
            'target' => $this->target,
            'completion_event' => $this->completionEvent,
            'budgeted_based_on_tasks' => $this->budgetedBasedOnTasks,
            'budgeted_amount' => $this->budgetedAmount,
            'percent_complete' => $this->percentComplete,
            'received_date' => $this->receivedDate,
            'actual_start_date' => $this->actualStartDate,
            'actual_complete_date' => $this->actualCompleteDate,
            'project_template_KEY' => $this->projectTemplateKey,
            'project_template_id' => $this->projectTemplateId,
            'original_due_date' => $this->originalDueDate,
            'extension_number' => $this->extensionNumber,
            'schedule_item' => $this->scheduleItem,
            'tasks' => array_map(fn (Task $t) => $t->toArray(), $this->tasks),
            'extensions' => $this->extensions,
        ];
    }
}
