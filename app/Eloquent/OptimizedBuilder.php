<?php

namespace App\Eloquent;

use App\Traits\Query\QueryToSqlConverter;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;
use Laravel\Octane\Exceptions\DdException;

class OptimizedBuilder extends Builder
{
    use QueryToSqlConverter;

    /**
     * dd() with bindings
     * @return void
     * @throws DdException
     */
    #[NoReturn] public function bdd(): void
    {
        dd($this->getSqlWithBindings($this));
    }

    /**
     * @inheritDoc
     */
    public function hasNested($relations, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
    {
        $relations = explode('.', $relations);

        $doesntHave = $operator === '<' && $count === 1;

        if ($doesntHave) {
            $operator = '>=';
            $count = 1;
        }

        $closure = function ($q) use (&$closure, &$relations, $operator, $count, $callback) {
            count($relations) > 1
                ? $q->select(DB::raw('1'))->whereHas(array_shift($relations), $closure)
                : $q->select(DB::raw('1'))->has(array_shift($relations), $operator, $count, 'and', $callback);
        };

        return $this->has(array_shift($relations), $doesntHave ? '<' : '>=', 1, $boolean, $closure);
    }

    /**
     * @inheritDoc
     */
    public function hasMorph($relation, $types, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null)
    {
        if (is_string($relation)) {
            $relation = $this->getRelationWithoutConstraints($relation);
        }

        $types = (array)$types;

        if ($types === ['*']) {
            $types = $this->model->newModelQuery()->distinct()->pluck($relation->getMorphType())->filter()->all();
        }

        foreach ($types as &$type) {
            $type = Relation::getMorphedModel($type) ?? $type;
        }

        return $this->where(function ($query) use ($relation, $callback, $operator, $count, $types) {
            $query->select(DB::raw('1'));
            foreach ($types as $type) {
                $query->orWhere(function ($query) use ($relation, $callback, $operator, $count, $type) {
                    $belongsTo = $this->getBelongsToRelation($relation, $type);

                    if ($callback) {
                        $callback = function ($query) use ($callback, $type) {
                            return $callback($query, $type);
                        };
                    }

                    $query->where($this->qualifyColumn($relation->getMorphType()), '=', (new $type)->getMorphClass())
                        ->whereHas($belongsTo, $callback, $operator, $count);
                });
            }
        }, null, null, $boolean);
    }

    /**
     * @inheritDoc
     */
    public function whereHas($relation, Closure $callback = null, $operator = '>=', $count = 1)
    {
        if (is_null($callback)) {
            return parent::whereHas($relation, fn($query) => $query->select(DB::raw('1')), $operator, $count);
        } else {
            return parent::whereHas($relation, function ($query) use ($callback) {
                $result = $callback($query->select(DB::raw('1')));
                return is_null($result) ? fn($query) => $query->select(DB::raw('1')) : $result;
            }, $operator, $count);
        }
    }

    /**
     * @inheritDoc
     */
    public function orWhereHas($relation, Closure $callback = null, $operator = '>=', $count = 1)
    {
        if (is_null($callback)) {
            return parent::orWhereHas($relation, fn($query) => $query->select(DB::raw('1')), $operator, $count);
        } else {
            return parent::orWhereHas($relation, function ($query) use ($callback) {
                $result = $callback($query->select(DB::raw('1')));
                return is_null($result) ? fn($query) => $query->select(DB::raw('1')) : $result;
            }, $operator, $count);
        }
    }

    /**
     * @inheritDoc
     */
    public function whereDoesntHave($relation, Closure $callback = null)
    {
        if (is_null($callback)) {
            return parent::whereDoesntHave($relation, fn($query) => $query->select(DB::raw('1')));
        } else {
            return parent::whereDoesntHave($relation, function ($query) use ($callback) {
                $result = $callback($query->select(DB::raw('1')));
                return is_null($result) ? fn($query) => $query->select(DB::raw('1')) : $result;
            });
        }
    }

    /**
     * @inheritDoc
     */
    public function orWhereDoesntHave($relation, Closure $callback = null)
    {
        if (is_null($callback)) {
            return parent::orWhereDoesntHave($relation, fn($query) => $query->select(DB::raw('1')));
        } else {
            return parent::orWhereDoesntHave($relation, function ($query) use ($callback) {
                $result = $callback($query->select(DB::raw('1')));
                return is_null($result) ? fn($query) => $query->select(DB::raw('1')) : $result;
            });
        }
    }

    /**
     * @inheritDoc
     */
    public function exists()
    {
        return $this->getQuery()->select(DB::raw('1'))->exists();
    }
}
