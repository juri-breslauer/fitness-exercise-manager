<?php

namespace App\Policies;

use App\Models\User;

abstract class CatalogPolicy
{
    public function viewAny(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(?User $user, mixed $record = null): bool
    {
        return $this->isAdmin($user);
    }

    public function create(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(?User $user, mixed $record = null): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(?User $user, mixed $record = null): bool
    {
        return $this->isAdmin($user);
    }

    public function deleteAny(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function restore(?User $user, mixed $record = null): bool
    {
        return $this->isAdmin($user);
    }

    public function restoreAny(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function forceDelete(?User $user, mixed $record = null): bool
    {
        return $this->isAdmin($user);
    }

    public function forceDeleteAny(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function replicate(?User $user, mixed $record = null): bool
    {
        return $this->isAdmin($user);
    }

    public function reorder(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    protected function isAdmin(?User $user): bool
    {
        return $user?->isAdmin() === true;
    }
}
