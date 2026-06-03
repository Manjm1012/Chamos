<?php

namespace App\Policies;

use App\Models\Maintenance;
use App\Models\User;

class MaintenancePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasTenant($user) && $this->canRead($user);
    }

    public function view(User $user, Maintenance $maintenance): bool
    {
        return $this->isInSameTenant($user, $maintenance) && $this->canRead($user);
    }

    public function create(User $user): bool
    {
        return $this->hasTenant($user) && $this->canWrite($user);
    }

    public function update(User $user, Maintenance $maintenance): bool
    {
        return $this->isInSameTenant($user, $maintenance) && $this->canWrite($user);
    }

    public function delete(User $user, Maintenance $maintenance): bool
    {
        return $this->isInSameTenant($user, $maintenance) && $this->canDelete($user);
    }

    public function restore(User $user, Maintenance $maintenance): bool
    {
        return $this->isInSameTenant($user, $maintenance) && $this->canDelete($user);
    }

    public function forceDelete(User $user, Maintenance $maintenance): bool
    {
        return $this->isInSameTenant($user, $maintenance) && $this->canDelete($user);
    }

    protected function hasTenant(User $user): bool
    {
        return ! empty($user->tenant_id);
    }

    protected function isInSameTenant(User $user, Maintenance $maintenance): bool
    {
        return $this->hasTenant($user) && $user->tenant_id === $maintenance->tenant_id;
    }

    protected function canRead(User $user): bool
    {
        return in_array($user->role, ['admin', 'operative'], true)
            && $this->tokenAllows($user, 'maintenance:read');
    }

    protected function canWrite(User $user): bool
    {
        return in_array($user->role, ['admin', 'operative'], true)
            && $this->tokenAllows($user, 'maintenance:write');
    }

    protected function canDelete(User $user): bool
    {
        return $user->role === 'admin'
            && $this->tokenAllows($user, 'maintenance:delete');
    }

    protected function tokenAllows(User $user, string $ability): bool
    {
        if (! $user->currentAccessToken()) {
            return true;
        }

        return $user->tokenCan('*') || $user->tokenCan($ability);
    }
}
