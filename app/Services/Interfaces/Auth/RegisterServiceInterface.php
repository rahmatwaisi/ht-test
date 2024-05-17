<?php

namespace App\Services\Interfaces\Auth;

use App\Models\User;

interface RegisterServiceInterface
{
    /**
     * Registers the user in the platform.
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User;
}
