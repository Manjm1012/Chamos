<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;

class EquipmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasTenant($user) && $this->canRead($user);
    }

    public function view(User $user, Equipment $equipment): bool
    {
        return $this->isInSameTenant($user, $equipment) && $this->canRead($user);
    }

    public function create(User $user): bool
    {
        return $this->hasTenant($user) && $this->canWrite($user);
    }

    public function update(User $user, Equipment $equipment): bool
    {
        return $this->isInSameTenant($user, $equipment) && $this->canWrite($user);
    }

    public function delete(User $user, Equipment $equipment): bool
    {
        return $this->isInSameTenant($user, $equipment) && $this->canDelete($user);
    }

    public function restore(User $user, Equipment $equipment): bool
    {
        return $this->isInSameTenant($user, $equipment) && $this->canDelete($user);
    }

    public function forceDelete(User $user, Equipment $equipment): bool
    {
        return $this->isInSameTenant($user, $equipment) && $this->canDelete($user);
    }

    protected function hasTenant(User $user): bool
    {
        return ! empty($user->tenant_id);
    }

    protected function isInSameTenant(User $user, Equipment $equipment): bool
    {
        return $this->hasTenant($user) && $user->tenant_id === $equipment->tenant_id;
    }

    protected function canRead(User $user): bool
    {
        return in_array($user->role, ['admin', 'operative'], true)
            && $this->tokenAllows($user, 'equipment:read');
    }

    protected function canWrite(User $user): bool
    {
        return in_array($user->role, ['admin', 'operative'], true)
            && $this->tokenAllows($user, 'equipment:write');
    }

    protected function canDelete(User $user): bool
    {
        return $user->role === 'admin'
            && $this->tokenAllows($user, 'equipment:delete');
    }

    protected function tokenAllows(User $user, string $ability): bool
    {
        if (! $user->currentAccessToken()) {
            return true;
        }

        return $user->tokenCan('*') || $user->tokenCan($ability);
    }
}
