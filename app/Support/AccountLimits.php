<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Generous per-account ceilings that apply to every user, not just the public
 * demo account (see DemoAccount for its much stricter limits). These exist so
 * a malicious signup can't do the same database-bloat abuse the demo guard
 * prevents, just one registration step removed.
 */
class AccountLimits
{
    public const MAX_GAMES = 500;
    public const MAX_FRIENDS = 300;
    public const MAX_SESSIONS = 500;
}
