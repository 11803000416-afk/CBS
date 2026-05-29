<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([User::ROLE_ADMIN, User::ROLE_SELLER, User::ROLE_BUYER]);
    }

    public function view(User $user, Transaction $transaction): bool
    {
        if ($user->hasRole(User::ROLE_ADMIN)) {
            return true;
        }

        if ($user->hasRole(User::ROLE_SELLER)) {
            return optional($transaction->seller)->user_id === $user->id;
        }

        if ($user->hasRole(User::ROLE_BUYER)) {
            return optional($transaction->buyer)->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole([User::ROLE_ADMIN, User::ROLE_SELLER]);
    }

    public function update(User $user, Transaction $transaction): bool
    {
        if ($user->hasRole(User::ROLE_ADMIN)) {
            return true;
        }

        if ($user->hasRole(User::ROLE_SELLER)) {
            return optional($transaction->seller)->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->hasRole(User::ROLE_ADMIN);
    }
}
