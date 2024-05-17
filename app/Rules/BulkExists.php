<?php

namespace App\Rules;

use App\Services\Contract\LocationServiceInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Throwable;

class BulkExists implements Rule
{
    /**
     * @var string
     */
    protected string $table;

    /**
     * @var string
     */
    protected string $column;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table, string $column = 'id')
    {
        $this->table = $table;
        $this->column = $column;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws Throwable
     */
    public function passes($attribute, $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        $value = array_unique($value,SORT_NUMERIC);

        return DB::table($this->table)->whereIn($this->column, $value)->count() == count($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be an array of valid ids.';
    }
}
