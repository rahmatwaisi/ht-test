<?php

namespace App\Services\Implements;

use App\Events\TaskCompleted;
use App\Models\User;
use App\Services\Interfaces\TaskServiceInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class TaskService implements TaskServiceInterface
{

    private Authenticatable|User|null $user;

    public function __construct()
    {
        $this->user = request()->user();
    }

    public function storeTask(array $data)
    {
        $data['slug'] = Str::slug($data['title']);
        return $this->user->tasks()->create($data);
    }

    public function showTask(int $id)
    {
        return $this->user->tasks()->findOrFail($id);
    }

    public function listTasks(array $filters)
    {
        return $this->user->tasks()
            ->when(
                !empty($filters),
                fn($query) => $query->when(
                    isset($filters['status']),
                    fn($query) => $query->where('status', $filters['status'])
                )->when(
                    isset($filters['title']),
                    fn($query) => $query->where('title', 'like', sprintf('%%%s%%', $filters['title']))
                )->whereDateRange([
                    'date' => data_get($filters, 'date'),
                    'from' => data_get($filters, 'from'),
                    'to' => data_get($filters, 'to'),
                ])->applySorting($filters['sorting'] ?? [])
            )->paginate($filters['limit'] ?? config('api.pagination.size.default'));
    }

    public function updateTasks(array $updates):bool
    {
        $query = $this->user->tasks()->whereIn('id', $updates['task_ids']);

        (clone $query)->update([
            'status' => $updates['status'],
        ]);

        foreach ((clone $query)->cursor() as $task) {
            broadcast(new TaskCompleted($task));
        }

        return true;
    }

}
