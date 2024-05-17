<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCompleted implements ShouldBroadcast, ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithSockets, SerializesModels;

    public const QUEUE = "broadcasting-event:task-complement";

    /**
     * Create a new event instance.
     */
    public function __construct(
        protected Task $task
    )
    {
        self::onQueue(self::QUEUE);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('tasks.' . $this->task->id);
    }


    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->task->id,
            'title' => $this->task->title,
            'description' => $this->task->description,
            'status' => $this->task->status,
            'completed_at' => $this->task->completed_at,
        ];
    }
}
