<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Repositories\Contracts\ContactMessageRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactMessageService
{
    public function __construct(protected ContactMessageRepositoryInterface $messages) {}

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->messages->paginate($filters, $perPage);
    }

    public function create(array $data): ContactMessage
    {
        return $this->messages->create($data);
    }

    public function delete(ContactMessage $message): void
    {
        $this->messages->delete($message);
    }
}
