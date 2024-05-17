<?php

namespace App\Console\Commands;

use App\Events\TaskCompleted;
use App\Jobs\CompleteTask;
use App\Notifications\UserRegistered;
use Illuminate\Console\Command;

class QueueWork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:process {--queue=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Override the default queue:work command.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queues = collect(['default']);

        $queues->push($this->getEmailsQueueNames());
        $queues->push($this->getEventQueueNames());
        $queues->push($this->getDefaultQueueNames());
        $queues->push($this->getJobsQueueNames());

        $queue_names = implode(',', $queues->flatten()->unique()->toArray());

        return $this->call('queue:work', ['--queue' => $queue_names]);
    }

    public function getJobsQueueNames(): array
    {
        return [
            CompleteTask::QUEUE
        ];
    }

    public function getEmailsQueueNames(): array
    {
        return [
            UserRegistered::QUEUE
        ];
    }

    public function getDefaultQueueNames(): array
    {
        return [
            'default',
        ];
    }

    public function getEventQueueNames(): array
    {
        return [
            TaskCompleted::QUEUE,
        ];
    }

}
