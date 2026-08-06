<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Rider;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Every login goes through /login. This resolves one email + password against
 * each portal's own table and signs the account into the matching guard.
 *
 * Order matters: admins first, then riders, then clients. The same address may
 * legitimately exist in more than one table (an ops manager who also rides),
 * and the earlier guard wins.
 */
class PortalAuthenticator
{
    /**
     * Guard name => how to find the account and where it lands after login.
     *
     * @var array<string, array{model: class-string, dashboard: string, label: string}>
     */
    protected const PORTALS = [
        'web' => ['model' => User::class, 'dashboard' => 'dashboard', 'label' => 'admin'],
        'rider' => ['model' => Rider::class, 'dashboard' => 'rider.dashboard', 'label' => 'rider'],
        'client' => ['model' => Company::class, 'dashboard' => 'client.dashboard', 'label' => 'client'],
    ];

    /**
     * Sign the credentials into whichever portal owns them.
     *
     * @param  array{email: string, password: string}  $credentials
     * @return string The guard the account was signed into.
     *
     * @throws ValidationException
     */
    public function attempt(Request $request, array $credentials, bool $remember = false): string
    {
        $match = $this->resolve($credentials);

        if (! $match) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        [$guard, $account] = $match;

        if (! $this->canAccessPortal($account)) {
            throw ValidationException::withMessages([
                'email' => $this->inactiveMessage($guard),
            ]);
        }

        // A second portal must never inherit the first one's session.
        $this->logoutOtherGuards($guard);

        Auth::guard($guard)->login($account, $remember);

        if (in_array($guard, ['rider', 'client'], true)) {
            $account->forceFill(['last_login_at' => now()])->save();
        }

        $request->session()->regenerate();

        return $guard;
    }

    /**
     * Find the first portal whose table holds these credentials.
     *
     * Every portal is checked even after a match so the work — and therefore the
     * response time — does not reveal which table an address lives in.
     *
     * @param  array{email: string, password: string}  $credentials
     * @return array{0: string, 1: Authenticatable}|null
     */
    protected function resolve(array $credentials): ?array
    {
        $match = null;

        foreach (self::PORTALS as $guard => $portal) {
            /** @var class-string<Model> $model */
            $model = $portal['model'];

            $account = $model::query()->where('email', $credentials['email'])->first();

            $valid = $account
                && $account->password
                && Hash::check($credentials['password'], $account->password);

            if ($valid && ! $match) {
                $match = [$guard, $account];
            }
        }

        return $match;
    }

    /**
     * Admins have no portal gate; riders and clients must be active.
     */
    protected function canAccessPortal(Authenticatable $account): bool
    {
        return ! method_exists($account, 'canAccessPortal') || $account->canAccessPortal();
    }

    protected function inactiveMessage(string $guard): string
    {
        return match ($guard) {
            'rider' => 'Your rider account is not active. Please contact your operations manager.',
            'client' => 'Your company account is not active. Please contact your account manager.',
            default => 'This account is not active.',
        };
    }

    public function logoutOtherGuards(?string $keep = null): void
    {
        foreach (array_keys(self::PORTALS) as $guard) {
            if ($guard !== $keep) {
                Auth::guard($guard)->logout();
            }
        }
    }

    /**
     * The guard the current request is signed into, if any.
     */
    public function currentGuard(): ?string
    {
        foreach (array_keys(self::PORTALS) as $guard) {
            if (Auth::guard($guard)->check()) {
                return $guard;
            }
        }

        return null;
    }

    /**
     * Where a guard's dashboard lives — the single source of truth for
     * post-login redirects, so login, logout and middleware never disagree.
     */
    public function dashboardFor(?string $guard): string
    {
        return route(self::PORTALS[$guard]['dashboard'] ?? 'login');
    }

    /**
     * The dashboard for whoever is signed in right now, or the login page.
     */
    public function homeForCurrentUser(): string
    {
        return $this->dashboardFor($this->currentGuard());
    }
}
