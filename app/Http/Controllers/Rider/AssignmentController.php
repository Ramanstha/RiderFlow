<?php

namespace App\Http\Controllers\Rider;

use App\Services\RiderAssignmentService;
use Illuminate\View\View;

class AssignmentController extends PortalController
{
    public function __construct(protected RiderAssignmentService $assignments) {}

    public function index(): View
    {
        return view('rider.assignment.index', [
            'assignments' => $this->assignments->paginate(['rider_id' => $this->rider()->id]),
        ]);
    }
}
