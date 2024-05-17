<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatus;
use App\Rules\BulkExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkUpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string>
     */
    public function rules(): array
    {
        return [
            'status' => ['bail', 'required', 'string', Rule::in(TaskStatus::toArray())],
            'task_ids' => ['required', 'array', new BulkExists('tasks')]
        ];
    }
}
