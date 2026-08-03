<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Shared base for the rider portal. Every query in the portal is scoped to
 * the signed-in rider, so controllers resolve it from here rather than
 * trusting anything on the request.
 */
abstract class PortalController extends Controller
{
    protected function rider(): Rider
    {
        /** @var Rider $rider */
        $rider = Auth::guard('rider')->user();

        return $rider;
    }

    /**
     * Guard record-level access: a rider may only touch their own rows.
     */
    protected function authorizeOwnership(int $riderId): void
    {
        if ($riderId !== $this->rider()->id) {
            throw new AccessDeniedHttpException('This record does not belong to you.');
        }
    }
}
