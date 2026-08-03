<?php

namespace App\Http\Controllers\Client;

use App\Services\ClientPortalService;
use Illuminate\View\View;

class DashboardController extends PortalController
{
    public function __construct(protected ClientPortalService $portal) {}

    public function index(): View
    {
        $company = $this->company();

        return view('client.dashboard.index', [
            'company' => $company,
            'stats' => $this->portal->dashboard($company),
        ]);
    }
}
