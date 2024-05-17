<?php

namespace App\Enums;

enum TaskStatus: string
{
    case DRAFT = 'draft';
    case COMPLETED = 'completed';

    /**
     * @return array<string>
     */
    public static function toArray(): array
    {
        return [
            self::DRAFT->value,
            self::COMPLETED->value,
        ];
    }
}
