<?php

namespace App\Scopes;

trait HasSortingScope
{
    public string $sortByColumn = 'created_at';
    public string $sortByDirection = 'desc';

    public function scopeApplySorting($query, array $sorting)
    {
        $by = sprintf('%s.%s', $this->getTable(), $sorting['by'] ?? $this->sortByColumn);

        $dir = $sorting['dir'] ?? $this->sortByDirection;

        return $query->orderBy($by, $dir);
    }

}
