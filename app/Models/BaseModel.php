<?php

namespace App\Models;

use App\Scopes\DisablesTimestamps;
use App\Traits\Eloquent\OptimizedEloquentBuilder;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use OptimizedEloquentBuilder;
    use DisablesTimestamps;
}
