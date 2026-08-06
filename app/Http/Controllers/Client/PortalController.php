<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

/**
 * Shared base for the client portal. Every query is scoped to the signed-in
 * company, which is resolved from the guard here rather than from anything on
 * the request — a client must never be able to ask for another company's data.
 */
abstract class PortalController extends Controller
{
    protected function company(): Company
    {
        /** @var Company $company */
        $company = Auth::guard('client')->user();

        return $company;
    }

    /**
     * Report filters a client is allowed to set, with the company pinned to
     * their own id no matter what arrives in the query string.
     *
     * @return array<string, mixed>
     */
    protected function scopedFilters(array $filters): array
    {
        $riderIds = $this->company()->riders()->pluck('riders.id');

        // A rider_id from another company would otherwise widen the scope.
        if (isset($filters['rider_id']) && ! $riderIds->contains((int) $filters['rider_id'])) {
            unset($filters['rider_id']);
        }

        return array_merge($filters, ['company_id' => $this->company()->id]);
    }
}
