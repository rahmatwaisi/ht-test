<?php

namespace App\Services\Implements\Auth;

use App\Models\User;
use App\Services\Interfaces\Auth\LoginServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginService implements LoginServiceInterface
{
    /**
     * @inheritDoc
     */
    public function login(array $credentials): string
    {
        /** @var User $user */
        $user = User::query()->where('email', data_get($credentials, 'email'))->first();

        if (empty($user) || !Hash::check(data_get($credentials, 'password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => [ __('auth.invalid_credentials')],
            ]);
        }

        return $user->createToken('auth_token')->plainTextToken;
    }
}
