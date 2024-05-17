<?php

namespace App\Scopes;

use App\Models\BaseModel;

trait DisablesTimestamps
{
    /**
     * Temporarily disable timestamps
     *
     * @return $this
     */
    public function scopeWithoutTimestamps(): BaseModel
    {
        $this->timestamps = false;
        return $this;
    }
}
