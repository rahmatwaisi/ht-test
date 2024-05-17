<?php

namespace App\Services\Implements\Auth;

use App\Models\User;
use App\Services\Interfaces\Auth\LogoutServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class LogoutService implements LogoutServiceInterface
{

    /**
     * @inheritDoc
     */
    public function logout(): bool
    {
        /** @var User $user */
        $user = auth()->user();

        abort_if(empty($user), Response::HTTP_FORBIDDEN, 'Logout failed.');

        return $user->currentAccessToken()->delete();
    }
}
