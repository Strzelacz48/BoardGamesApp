<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Session;
use App\Models\User;
use App\Support\AccountLimits;
use App\Support\DemoAccount;

class SessionPolicy
{
    public function create(User $user): bool
    {
        $limit = DemoAccount::isDemo($user) ? DemoAccount::MAX_SESSIONS : AccountLimits::MAX_SESSIONS;

        return Session::where("user_id", $user->id)->count() < $limit;
    }

    public function view(User $user, Session $session): bool
    {
        return $session->user_id === $user->id;
    }

    public function update(User $user, Session $session): bool
    {
        return $session->user_id === $user->id;
    }

    public function delete(User $user, Session $session): bool
    {
        return $session->user_id === $user->id;
    }

    public function arrange(User $user, Session $session): bool
    {
        return $session->user_id === $user->id;
    }
}
