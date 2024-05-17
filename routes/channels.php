<?php

use App\Broadcasting\TaskChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});


Broadcast::channel('tasks.{task}', TaskChannel::class);
