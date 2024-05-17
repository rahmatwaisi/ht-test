<?php

namespace App\Services\Interfaces\Auth;

interface LogoutServiceInterface
{
    /**
     * Logouts the user from the system and revokes the access token.
     */
    public function logout(): bool;
}
