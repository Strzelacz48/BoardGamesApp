<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Game;
use App\Models\User;
use App\Support\DemoAccount;

class GamePolicy
{
    public function create(User $user): bool
    {
        return !DemoAccount::isDemo($user) || Game::where("user_id", $user->id)->count() < DemoAccount::MAX_GAMES;
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
