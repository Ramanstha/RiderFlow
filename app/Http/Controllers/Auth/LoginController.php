<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginAdminRequest;
use App\Services\PortalAuthenticator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * The single front door. Admins, riders and clients all sign in here and are
 * sent to their own dashboard based on which table their credentials live in.
 */
class LoginController extends Controller
{
    public function __construct(protected PortalAuthenticator $portals) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginAdminRequest $request): RedirectResponse
    {
        $guard = $this->portals->attempt(
            $request,
            $request->validated(),
            $request->boolean('remember'),
        );

        // An intended URL saved by one portal must not drag another portal's
        // account somewhere it has no access to, so only honour it when it
        // belongs to the guard that actually signed in.
        $intended = $request->session()->pull('url.intended');

        if ($intended && $this->belongsToPortal($intended, $guard)) {
            return redirect()->to($intended);
        }

        return redirect()->to($this->portals->dashboardFor($guard));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->portals->logoutOtherGuards();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Is this intended URL inside the portal the account just signed into?
     */
    protected function belongsToPortal(string $intended, string $guard): bool
    {
        $path = '/'.ltrim(parse_url($intended, PHP_URL_PATH) ?? '', '/');

        $prefix = match ($guard) {
            'rider' => '/rider-portal',
            'client' => '/client-portal',
            default => null,
        };

        // Admin owns everything that is not another portal's prefix.
        if ($prefix === null) {
            return ! str_starts_with($path, '/rider-portal') && ! str_starts_with($path, '/client-portal');
        }

        return str_starts_with($path, $prefix);
    }
}
