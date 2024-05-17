<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\BulkUpdateTaskRequest;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\FilterTaskRequest;
use App\Http\Resources\CustomLengthAwarePagination;
use App\Http\Resources\TaskResource;
use App\Services\Interfaces\TaskServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class TaskController extends Controller
{

    private TaskServiceInterface $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(CreateTaskRequest $request): JsonResponse
    {

        $task = $this->taskService->storeTask($request->validated());

        return response()->json([
            'result' => new TaskResource($task)
        ], HttpStatus::HTTP_CREATED);
    }

    public function bulkUpdate(BulkUpdateTaskRequest $request)
    {
        $updated = $this->taskService->updateTasks($request->validated());

        $modelName = Str::plural('Task');

        return response()->json(
            [
                'result' =>[
                    'message'=> $updated
                        ? __('database.bulk_updated', ['model' => $modelName])
                        : __('database.bulk_update_failed', ['model' => $modelName]),
                    'success'=>$updated
                ]
            ],
            $updated ? HttpStatus::HTTP_OK : HttpStatus::HTTP_NOT_MODIFIED
        );
    }

    public function show($id)
    {
        $task = $this->taskService->showTask($id);

        return response()->json([
            'result' => new TaskResource($task)
        ]);
    }

    public function index(FilterTaskRequest $request): JsonResponse
    {
        $tasks = $this->taskService->listTasks($request->validated());

        return response()->json(
            CustomLengthAwarePagination::build($tasks, TaskResource::class)
        );
    }
}
