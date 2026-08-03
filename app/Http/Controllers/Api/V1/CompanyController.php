<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends BaseApiController
{
    public function __construct(protected CompanyService $companies) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('companies.view');

        $companies = $this->companies->paginate($request->only(['search', 'status']));

        return $this->success(CompanyResource::collection($companies));
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $this->authorize('companies.manage');

        $company = $this->companies->create($request->validated());

        return $this->success(new CompanyResource($company), 'Company created successfully.', 201);
    }

    public function show(Company $company): JsonResponse
    {
        $this->authorize('companies.view');

        return $this->success(new CompanyResource($company));
    }

    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        $this->authorize('companies.manage');

        $company = $this->companies->update($company, $request->validated());

        return $this->success(new CompanyResource($company), 'Company updated successfully.');
    }

    public function destroy(Company $company): JsonResponse
    {
        $this->authorize('companies.manage');

        $this->companies->delete($company);

        return $this->success(message: 'Company removed successfully.');
    }
}
