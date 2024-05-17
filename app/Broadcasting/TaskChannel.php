<?php

namespace App\Broadcasting;

use App\Models\Task;
use App\Models\User;

class TaskChannel
{

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, Task $task): array|bool
    {
        return $user->id === $task->owner_id;
    }
}
