<?php

namespace App\Services\Implements\Auth;

use App\Models\User;
use App\Notifications\UserRegistered;
use App\Services\Interfaces\Auth\RegisterServiceInterface;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegisterService implements RegisterServiceInterface
{

    /**
     * @param string $email
     * @return void
     * @throws HttpException
     */
    private function ensureUserDoesntExists(string $email): void
    {
        abort_if(
            User::query()->where('email', $email)->exists(),
            Response::HTTP_CONFLICT,
            __('auth.user_exists')
        );
    }

    /**
     * @inheritDoc
     */
    public function register(array $data): User
    {
        $this->ensureUserDoesntExists($data['email']);

        /** @var User $user */
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(), // we can send an email containing a link to follow or the verification code itself.api
        ]);

        $user->notify(new UserRegistered());

        return $user;
    }
}
