<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\DeleteProfileAction;
use App\Actions\UpdateProfileAction;
use App\Http\Requests\DeleteProfileRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render("Profile/Edit", [
            "mustVerifyEmail" => $request->user() instanceof MustVerifyEmail,
            "status" => session("status"),
        ]);
    }

    public function update(ProfileUpdateRequest $request, UpdateProfileAction $action): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated["email"] !== $request->user()->email) {
            $this->authorizeOrFail(
                "updateEmail",
                $request->user(),
                "email",
                "This is a shared public demo account, so its email address can't be changed.",
            );
        }

        $action->execute($request->user(), $validated);

        return Redirect::route("profile.edit");
    }

    public function destroy(DeleteProfileRequest $request, DeleteProfileAction $action): RedirectResponse
    {
        $this->authorizeOrFail(
            "delete",
            $request->user(),
            "password",
            "This is a shared public demo account and can't be deleted.",
        );

        $action->execute($request->user());

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to("/");
    }
}
