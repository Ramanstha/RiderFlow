<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    public function __construct(protected ContactRepositoryInterface $contacts) {}

    public function list(array $filters = []): Collection
    {
        return $this->contacts->all($filters);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->contacts->paginate($filters, $perPage);
    }

    public function primary(): ?Contact
    {
        return $this->contacts->all()->last();
    }

    public function find(int $id): Contact
    {
        return $this->contacts->findOrFail($id);
    }

    public function create(array $data): Contact
    {
        return $this->contacts->create($data);
    }

    public function update(Contact $contact, array $data): Contact
    {
        return $this->contacts->update($contact, $data);
    }

    public function delete(Contact $contact): void
    {
        $this->contacts->delete($contact);
    }
}
