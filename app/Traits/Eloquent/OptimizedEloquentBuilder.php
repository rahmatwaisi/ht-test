<?php

namespace App\Traits\Eloquent;

use App\Eloquent\OptimizedBuilder;
use Illuminate\Database\Query\Builder;

trait OptimizedEloquentBuilder
{
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param Builder|object $query
     * @return OptimizedBuilder
     */
    public function newEloquentBuilder($query): OptimizedBuilder
    {
        return new OptimizedBuilder($query);
    }
}
