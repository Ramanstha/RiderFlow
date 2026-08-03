<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function __construct(protected CompanyService $companies) {}

    public function index(Request $request): View
    {
        $this->authorize('companies.view');

        $companies = $this->companies->paginate($request->only(['search', 'status']));

        return view('admin.company.index', compact('companies'));
    }

    public function create(): View
    {
        $this->authorize('companies.manage');

        return view('admin.company.create');
    }

    public function show(Company $company): RedirectResponse
    {
        $this->authorize('companies.view');

        return redirect()->route('company.edit', $company);
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $this->authorize('companies.manage');

        $this->companies->create($request->validated());

        return redirect()->route('company.index')->with('success', 'Company added successfully.');
    }

    public function edit(Company $company): View
    {
        $this->authorize('companies.manage');

        return view('admin.company.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $this->authorize('companies.manage');

        $this->companies->update($company, $request->validated());

        return redirect()->route('company.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $this->authorize('companies.manage');

        $this->companies->delete($company);

        return redirect()->route('company.index')->with('success', 'Company removed successfully.');
    }
}
