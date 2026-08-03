<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Report\ReportFilterRequest;
use App\Models\Company;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;

class ReportController extends BaseApiController
{
    public function __construct(protected ReportService $reports) {}

    public function attendance(ReportFilterRequest $request): JsonResponse
    {
        $this->authorize('reports.view');

        return $this->success($this->reports->attendanceSummary($request->validated()));
    }

    public function parcels(ReportFilterRequest $request): JsonResponse
    {
        $this->authorize('reports.view');

        return $this->success($this->reports->parcelSummary($request->validated()));
    }

    public function performance(ReportFilterRequest $request): JsonResponse
    {
        $this->authorize('reports.view');

        return $this->success($this->reports->performanceSummary($request->validated()));
    }

    public function companyOverview(ReportFilterRequest $request, Company $company): JsonResponse
    {
        $this->authorize('reports.view');

        return $this->success($this->reports->companyOverview($company, $request->validated()));
    }
}
