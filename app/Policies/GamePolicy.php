<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Game;
use App\Models\User;
use App\Support\AccountLimits;
use App\Support\DemoAccount;

class GamePolicy
{
    public function create(User $user): bool
    {
        $limit = DemoAccount::isDemo($user) ? DemoAccount::MAX_GAMES : AccountLimits::MAX_GAMES;

        return Game::where("user_id", $user->id)->count() < $limit;
    }

    public function view(User $user, Game $game): bool
    {
        return $game->is_shared || $game->user_id === $user->id;
    }

    public function update(User $user, Game $game): bool
    {
        return !$game->is_shared && $game->user_id === $user->id;
    }

    public function delete(User $user, Game $game): bool
    {
        return !$game->is_shared && $game->user_id === $user->id;
    }

    public function incrementCopies(User $user, Game $game): bool
    {
        return !$game->is_shared && $game->user_id === $user->id;
    }
}
