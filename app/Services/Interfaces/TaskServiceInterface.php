<?php

namespace App\Services\Interfaces;

interface TaskServiceInterface
{

    public function storeTask(array $data);

    public function updateTasks(array $updates): bool;

    public function showTask(int $id);

    public function listTasks(array $filters);
}
