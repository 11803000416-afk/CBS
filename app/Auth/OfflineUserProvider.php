<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\GenericUser;

class OfflineUserProvider implements UserProvider
{
    /** @var array<int,array> */
    protected array $users = [];

    public function __construct()
    {
        $raw = config('demo_users.users', []);

        foreach ($raw as $u) {
            $u['password'] = Hash::make($u['password']);
            $this->users[] = $u;
        }
    }

    public function retrieveById($identifier)
    {
        foreach ($this->users as $u) {
            if (($u['id'] ?? null) == $identifier || ($u['email'] ?? null) === $identifier) {
                return new GenericUser($u);
            }
        }

        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        // Not implemented for offline demo mode.
        return null;
    }

    public function updateRememberToken(UserContract $user, $token)
    {
        // noop for offline provider
    }

    public function retrieveByCredentials(array $credentials)
    {
        $email = $credentials['email'] ?? $credentials['username'] ?? null;

        if (! $email) {
            return null;
        }

        foreach ($this->users as $u) {
            if (($u['email'] ?? null) === $email) {
                return new GenericUser($u);
            }
        }

        return null;
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'] ?? null;

        if (! $plain) {
            return false;
        }

        return Hash::check($plain, $user->getAuthPassword());
    }
}
