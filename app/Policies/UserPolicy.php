<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('user-list');
    }

    public function view(User $user, User $model): bool
    {
        return $user->can('user-list');
    }

    public function create(User $user): bool
    {
        return $user->can('user-create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('user-edit');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can('user-delete');
    }
}
