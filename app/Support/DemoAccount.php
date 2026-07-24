<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Database\Seeders\DemoSeeder;

class DemoAccount
{
    public const MAX_GAMES = 30;
    public const MAX_FRIENDS = 20;
    public const MAX_SESSIONS = 20;

    public static function isDemo(?User $user): bool
    {
        return $user !== null && $user->email === DemoSeeder::DEMO_EMAIL;
    }
}
