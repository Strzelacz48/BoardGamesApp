<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

abstract class Controller
{
    use AuthorizesRequests;

    /**
     * Like authorize(), but reports a denial as a field-scoped validation error
     * instead of a 403 page, so it renders inline in the form that triggered it.
     */
    protected function authorizeOrFail(string $ability, mixed $target, string $field, string $message): void
    {
        if (Gate::denies($ability, $target)) {
            throw ValidationException::withMessages([$field => $message]);
        }
    }
}
