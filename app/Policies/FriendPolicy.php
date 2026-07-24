<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Friend;
use App\Models\User;
use App\Support\AccountLimits;
use App\Support\DemoAccount;

class FriendPolicy
{
    public function create(User $user): bool
    {
        $limit = DemoAccount::isDemo($user) ? DemoAccount::MAX_FRIENDS : AccountLimits::MAX_FRIENDS;

        return Friend::where("user_id", $user->id)->count() < $limit;
    }

    public function update(User $user, Friend $friend): bool
    {
        return $friend->user_id === $user->id;
    }

    public function delete(User $user, Friend $friend): bool
    {
        return $friend->user_id === $user->id;
    }

    public function managePreferences(User $user, Friend $friend): bool
    {
        return $friend->user_id === $user->id;
    }
}
