<?php

namespace App\Filament\Super\Resources\TenantResource\Pages;

use App\Filament\Super\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}