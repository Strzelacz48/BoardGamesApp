<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Support\DemoAccount;

class UserPolicy
{
    public function updateEmail(User $user, User $model): bool
    {
        return !DemoAccount::isDemo($model);
    }

    public function updatePassword(User $user, User $model): bool
    {
        return !DemoAccount::isDemo($model);
    }

    public function delete(User $user, User $model): bool
    {
        return !DemoAccount::isDemo($model);
    }
}
