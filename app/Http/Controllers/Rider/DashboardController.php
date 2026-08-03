<?php

namespace App\Http\Controllers\Rider;

use App\Services\RiderPortalService;
use Illuminate\View\View;

class DashboardController extends PortalController
{
    public function __construct(protected RiderPortalService $portal) {}

    public function index(): View
    {
        $rider = $this->rider()->load('company');

        return view('rider.dashboard.index', [
            'rider' => $rider,
            'stats' => $this->portal->dashboard($rider),
        ]);
    }
}
