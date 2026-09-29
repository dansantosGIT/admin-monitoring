<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $this->canManage($user);
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $this->update($user, $vehicle);
    }

    private function canManage(User $user): bool
    {
        return in_array($user->role ?? '', ['admin', 'super-admin'], true);
    }
}