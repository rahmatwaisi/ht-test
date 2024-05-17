<?php

namespace App\Traits\Query;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait QueryToSqlConverter
{
    /**
     * Converts a query into sql string with bindings.
     *
     * @param EloquentBuilder|QueryBuilder $query
     * @return string
     */
    public function getSqlWithBindings($query): string
    {
        return vsprintf(
            str_replace('?', '%s', $query->toSql()),
            collect($query->getBindings())->map(function ($binding) {
                return is_numeric($binding) ? $binding : "'{$binding}'";
            })->toArray()
        );
    }

}
