<?php

namespace App\Support\Tenancy;

use App\Models\Tenant;

class TenantContext
{
    protected static ?Tenant $tenant = null;

    public static function setTenant(Tenant $tenant): void
    {
        self::$tenant = $tenant;
    }

    public static function tenant(): ?Tenant
    {
        return self::$tenant;
    }

    public static function id(): ?int
    {
        return self::$tenant?->id;
    }

    public static function clear(): void
    {
        self::$tenant = null;
    }
}
