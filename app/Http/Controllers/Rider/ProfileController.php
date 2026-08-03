<?php

namespace App\Http\Controllers\Rider;

use App\Http\Requests\Rider\Portal\UpdatePasswordRequest;
use App\Http\Requests\Rider\Portal\UpdateProfileRequest;
use App\Services\RiderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends PortalController
{
    public function __construct(protected RiderService $riders) {}

    public function edit(): View
    {
        return view('rider.profile.edit', [
            'rider' => $this->rider()->load('company'),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['photo']);

        $this->riders->update($this->rider(), $data, $request->file('photo'));

        return redirect()->route('rider.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $this->riders->update($this->rider(), ['password' => $request->validated()['password']]);

        return redirect()->route('rider.profile.edit')->with('success', 'Password changed successfully.');
    }
}
