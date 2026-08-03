<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class CompanyService
{
    public function __construct(protected CompanyRepositoryInterface $companies) {}

    public function list(array $filters = []): Collection
    {
        return $this->companies->all($filters);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->companies->paginate($filters, $perPage);
    }

    public function find(int $id): Company
    {
        return $this->companies->findOrFail($id);
    }

    public function create(array $data): Company
    {
        return $this->companies->create($this->withoutBlankPassword($data));
    }

    public function update(Company $company, array $data): Company
    {
        return $this->companies->update($company, $this->withoutBlankPassword($data));
    }

    public function delete(Company $company): void
    {
        if ($company->riders()->exists()) {
            throw ValidationException::withMessages([
                'company' => 'This company still has riders assigned to it and cannot be removed.',
            ]);
        }

        $this->companies->delete($company);
    }

    /**
     * An empty password field means "leave the portal credentials alone",
     * never "wipe them".
     */
    protected function withoutBlankPassword(array $data): array
    {
        if (array_key_exists('password', $data) && blank($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }
}
