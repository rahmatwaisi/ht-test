<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterTaskRequest extends FormRequest
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
            'status' => ['nullable', 'string', Rule::in(TaskStatus::toArray())],
            'title' => ['nullable', 'string'],
            'limit' => [
                'nullable',
                'integer',
                sprintf('min:%d', config('api.pagination.size.default')),
                sprintf('max:%d', config('api.pagination.size.max'))
            ],
            'date' => ['nullable', 'string', 'in:created_at,updated_at,completed_at'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:dateRange.from'],
        ];
    }
}
