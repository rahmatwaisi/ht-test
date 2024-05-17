<?php

namespace App\Scopes;

use Illuminate\Support\Carbon;

trait HasDateRangeScope
{

    public string $dateRangeColumn = 'created_at';

    public function scopeWhereDateRange($query, array $range)
    {
        return $query->when(
            isset($range['from'], $range['to']),
            fn($query) => $query->whereBetween(
                $range['date'] ?? $this->dateRangeColumn, [
                    Carbon::parse($range['from'])->startOfDay(),
                    Carbon::parse($range['to'])->endOfDay()
                ])
        )->when(
            empty($range['to']) && isset($range['from']),
            fn($query) => $query->whereDate($range['date'] ?? $this->dateRangeColumn, '>=', Carbon::parse($range['from'])->startOfDay())
        )->when(
            empty($range['from']) && isset($range['to']),
            fn($query) => $query->whereDate($range['date'] ?? $this->dateRangeColumn, '<=', Carbon::parse($range['to'])->endOfDay())
        );
    }

}
