<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

abstract class Controller
{
    use AuthorizesRequests;

    protected function authorizeOrFail(string $ability, mixed $target, string|array $fields, string $message): void
    {
        if (Gate::denies($ability, $target)) {
            $fields = is_array($fields) ? $fields : [$fields];

            throw ValidationException::withMessages(array_fill_keys($fields, $message));
        }
    }
}
