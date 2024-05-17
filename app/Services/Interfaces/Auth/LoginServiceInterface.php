<?php

namespace App\Services\Interfaces\Auth;

use Illuminate\Validation\ValidationException;

interface LoginServiceInterface
{
    /**
     * @throws ValidationException
     */
    public function login(array $credentials): string;
}
