<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            "current_password" => ["required", "current_password"],
            "password" => ["required", Password::defaults(), "confirmed"],
        ]);

        $this->authorizeOrFail(
            "updatePassword",
            $request->user(),
            "current_password",
            "This is a shared public demo account, so its password can't be changed.",
        );

        $request->user()->update([
            "password" => Hash::make($validated["password"]),
        ]);

        return back();
    }
}
