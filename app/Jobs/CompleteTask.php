<?php

namespace App\Jobs;

use App\Enums\TaskStatus;
use App\Events\TaskCompleted;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CompleteTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const QUEUE = 'tasks:complete';

    protected int $taskId;


    /**
     * Create a new job instance.
     */
    public function __construct(Task $task)
    {
        $this->taskId = $task->id;
        self::onQueue(self::QUEUE);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var Task $task */
        $task = Task::query()->findOrFail($this->taskId);

        $task->update([
            'status' => TaskStatus::COMPLETED->value,
            'completed_at' => now()
        ]);

        broadcast(new TaskCompleted($task));
    }
}
