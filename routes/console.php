<?php

use App\Console\Commands\AutoCompleteTasks;
use Illuminate\Support\Facades\Schedule;

Schedule::command(AutoCompleteTasks::class)->everyMinute();
