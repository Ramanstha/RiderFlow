<?php

namespace App\Http\Controllers\Rider;

use App\Services\RiderAssignmentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends PortalController
{
    public function __construct(protected RiderAssignmentService $assignments) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['status']);
        $filters['rider_id'] = $this->rider()->id;

        return view('rider.assignment.index', [
            'assignments' => $this->assignments->paginate($filters),
        ]);
    }
}
