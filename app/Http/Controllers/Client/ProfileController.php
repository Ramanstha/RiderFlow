<?php

namespace App\Http\Controllers\Client;

use App\Http\Requests\Client\UpdateClientPasswordRequest;
use App\Http\Requests\Client\UpdateClientProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends PortalController
{
    public function edit(): View
    {
        return view('client.profile.edit', ['company' => $this->company()]);
    }

    public function update(UpdateClientProfileRequest $request): RedirectResponse
    {
        // Only contact details — a client cannot change its own name or status.
        $this->company()->update($request->validated());

        return redirect()->route('client.profile.edit')->with('success', 'Profile updated.');
    }

    public function updatePassword(UpdateClientPasswordRequest $request): RedirectResponse
    {
        $this->company()->forceFill(['password' => $request->validated()['password']])->save();

        return redirect()->route('client.profile.edit')->with('success', 'Password updated.');
    }
}
