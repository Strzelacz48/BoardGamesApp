<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Friend;
use App\Models\User;
use App\Support\DemoAccount;

class FriendPolicy
{
    public function create(User $user): bool
    {
        return !DemoAccount::isDemo($user) || Friend::where("user_id", $user->id)->count() < DemoAccount::MAX_FRIENDS;
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
