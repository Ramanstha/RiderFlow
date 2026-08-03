<?php

namespace App\Http\Controllers\Rider;

use App\Services\RiderPerformanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerformanceController extends PortalController
{
    public function __construct(protected RiderPerformanceService $performances) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to']);
        $filters['rider_id'] = $this->rider()->id;

        return view('rider.performance.index', [
            'performances' => $this->performances->paginate($filters),
        ]);
    }
}
