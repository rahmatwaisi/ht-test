<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Jobs\CompleteTask;
use App\Models\Task;
use Illuminate\Console\Command;

class AutoCompleteTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:auto-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Task::query()
            ->where('status', TaskStatus::DRAFT->value)
            ->where(
                'created_at',
                '<=',
                now()->subDays(env('COMPLETION_LIMIT_DAYS', 2))
            );

        $this->info('Auto-Completing Tasks...');
        $progressBar = $this->output->createProgressBar((clone $query)->count());
        $progressBar->start();
        foreach ((clone $query)->cursor() as $task) {
            $progressBar->advance();
            CompleteTask::dispatch($task);
        }
        $progressBar->finish();
        $this->output->newLine();
        $this->info('Done!');
    }
}
