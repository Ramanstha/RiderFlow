<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends PortalController
{
    public function index(Request $request): View
    {
        $company = $this->company();

        $assignments = $company->assignments()
            ->with('rider:id,name')
            ->when($request->integer('rider_id'), fn ($q, $id) => $q->where('rider_id', $id))
            ->when($request->string('status')->trim()->value(), fn ($q, $status) => $q->where('status', $status))
            ->latest('start_date')
            ->paginate(20)
            ->withQueryString();

        return view('client.assignment.index', [
            'assignments' => $assignments,
            'riders' => $company->riders()->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
