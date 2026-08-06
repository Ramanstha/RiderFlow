<?php

namespace App\Repositories\Contracts;

use App\Models\Contact;

interface ContactRepositoryInterface extends RepositoryInterface
{
    /**
     * The contact record the public site renders — the first one created.
     */
    public function primary(): ?Contact;
}
