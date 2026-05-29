<?php

namespace App\Policies;

use App\Models\Buyer;
use App\Models\User;

class BuyerPolicy
{
    private function canManage(User $user): bool
    {
        return $user->is_active && $user->hasRole(User::ROLE_ADMIN);
    }

    public function viewAny(User $user): bool
    {
        return $this->canManage($user);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function update(User $user, Buyer $buyer): bool
    {
        return $this->canManage($user);
    }

    public function delete(User $user, Buyer $buyer): bool
    {
        return $this->canManage($user);
    }
}
