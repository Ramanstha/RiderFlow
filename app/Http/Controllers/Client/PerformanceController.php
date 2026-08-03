<?php

namespace App\Http\Controllers\Client;

use App\Http\Requests\Report\ReportFilterRequest;
use App\Services\ReportService;
use Illuminate\View\View;

class PerformanceController extends PortalController
{
    public function __construct(protected ReportService $reports) {}

    public function index(ReportFilterRequest $request): View
    {
        $company = $this->company();

        return view('client.performance.index', [
            'summary' => $this->reports->performanceSummary($this->scopedFilters($request->validated())),
            'riders' => $company->riders()->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
