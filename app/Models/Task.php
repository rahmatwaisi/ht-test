<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Scopes\HasDateRangeScope;
use App\Scopes\HasSortingScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class App\Models\Task
 *
 * @property int $id
 * @property int $owner_id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property TaskStatus|string $status
 * @property Carbon|string|null $completed_at
 * @property Carbon|string|null $created_at
 * @property Carbon|string|null $updated_at
 *
 * @property-read User|object $owner
 *
 * @method self|Builder applySorting(array $sorting)
 * @method self|Builder whereDateRange(array $sorting)
 *
 * @see 2024_05_16_153357_create_tasks_table.php
 */
class Task extends BaseModel
{
    use HasFactory;
    use HasDateRangeScope;
    use HasSortingScope;

    protected $attributes = [
        'status' => TaskStatus::DRAFT->value
    ];

    protected $guarded = [
        'id'
    ];


    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
