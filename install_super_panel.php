<?php

/**
 * Script de instalación del panel Super Admin.
 * Ejecutar UNA VEZ con: php install_super_panel.php
 */

$base = __DIR__;

// ─── 1. Crear directorios ────────────────────────────────────────────────────
$dirs = [
    'app/Filament/Super/Resources/TenantResource/Pages',
    'app/Filament/Super/Resources/UserResource/Pages',
    'app/Filament/Super/Widgets',
    'app/Filament/Super/Pages',
];

foreach ($dirs as $dir) {
    $full = $base . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $dir);
    if (!is_dir($full)) {
        mkdir($full, 0755, true);
        echo "[OK] Directorio creado: $dir\n";
    } else {
        echo "[--] Ya existe: $dir\n";
    }
}

// ─── 2. Escribir archivos ────────────────────────────────────────────────────
$files = [];

// ── TenantResource ─────────────────────────────────────────────────────
$files['app/Filament/Super/Resources/TenantResource.php'] = <<<'PHP'
<?php

namespace App\Filament\Super\Resources;

use App\Filament\Super\Resources\TenantResource\Pages\CreateTenant;
use App\Filament\Super\Resources\TenantResource\Pages\EditTenant;
use App\Filament\Super\Resources\TenantResource\Pages\ListTenants;
use App\Models\Tenant;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Company name')
                ->required()
                ->maxLength(150),
            TextInput::make('slug')
                ->label('Slug (URL identifier)')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(80)
                ->helperText('Lowercase letters, numbers and hyphens only.'),
            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users')
                    ->sortable(),
                TextColumn::make('equipment_count')
                    ->counts('equipment')
                    ->label('Equipment')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'edit'   => EditTenant::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Tenants';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Tenants';
    }

    public static function getNavigationGroup(): string
    {
        return 'Platform Management';
    }
}
PHP;

$files['app/Filament/Super/Resources/TenantResource/Pages/ListTenants.php'] = <<<'PHP'
<?php

namespace App\Filament\Super\Resources\TenantResource\Pages;

use App\Filament\Super\Resources\TenantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
PHP;

$files['app/Filament/Super/Resources/TenantResource/Pages/CreateTenant.php'] = <<<'PHP'
<?php

namespace App\Filament\Super\Resources\TenantResource\Pages;

use App\Filament\Super\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}
PHP;

$files['app/Filament/Super/Resources/TenantResource/Pages/EditTenant.php'] = <<<'PHP'
<?php

namespace App\Filament\Super\Resources\TenantResource\Pages;

use App\Filament\Super\Resources\TenantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
PHP;

// ── UserResource ────────────────────────────────────────────────────────
$files['app/Filament/Super/Resources/UserResource.php'] = <<<'PHP'
<?php

namespace App\Filament\Super\Resources;

use App\Filament\Super\Resources\UserResource\Pages\CreateUser;
use App\Filament\Super\Resources\UserResource\Pages\EditUser;
use App\Filament\Super\Resources\UserResource\Pages\ListUsers;
use App\Models\Tenant;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use BackedEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(150),
            TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(200),
            TextInput::make('password')
                ->password()
                ->required(fn (string $operation): bool => $operation === 'create')
                ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->label('Password')
                ->helperText('Leave blank to keep current password when editing.'),
            Select::make('role')
                ->options([
                    'admin'       => 'Admin (tenant)',
                    'operative'   => 'Operative (tenant)',
                    'super_admin' => 'Super Admin',
                ])
                ->required()
                ->native(false),
            Select::make('tenant_id')
                ->label('Tenant')
                ->options(Tenant::query()->pluck('name', 'id'))
                ->searchable()
                ->nullable()
                ->helperText('Leave empty for Super Admin users.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin'       => 'warning',
                        default       => 'success',
                    }),
                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->placeholder('— Super Admin —')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'admin'       => 'Admin',
                        'operative'   => 'Operative',
                        'super_admin' => 'Super Admin',
                    ]),
                SelectFilter::make('tenant_id')
                    ->label('Tenant')
                    ->options(Tenant::query()->pluck('name', 'id')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Users';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Users';
    }

    public static function getNavigationGroup(): string
    {
        return 'Platform Management';
    }
}
PHP;

$files['app/Filament/Super/Resources/UserResource/Pages/ListUsers.php'] = <<<'PHP'
<?php

namespace App\Filament\Super\Resources\UserResource\Pages;

use App\Filament\Super\Resources\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
PHP;

$files['app/Filament/Super/Resources/UserResource/Pages/CreateUser.php'] = <<<'PHP'
<?php

namespace App\Filament\Super\Resources\UserResource\Pages;

use App\Filament\Super\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
PHP;

$files['app/Filament/Super/Resources/UserResource/Pages/EditUser.php'] = <<<'PHP'
<?php

namespace App\Filament\Super\Resources\UserResource\Pages;

use App\Filament\Super\Resources\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
PHP;

// ── Super Dashboard ─────────────────────────────────────────────────────
$files['app/Filament/Super/Pages/SuperDashboard.php'] = <<<'PHP'
<?php

namespace App\Filament\Super\Pages;

use App\Filament\Super\Widgets\PlatformStatsOverview;
use BackedEnum;
use Filament\Pages\Dashboard;

class SuperDashboard extends Dashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Platform Overview';

    public function getColumns(): int|array
    {
        return ['md' => 2, 'xl' => 4];
    }

    public function getWidgets(): array
    {
        return [
            PlatformStatsOverview::class,
        ];
    }
}
PHP;

// ── PlatformStatsOverview Widget ─────────────────────────────────────────
$files['app/Filament/Super/Widgets/PlatformStatsOverview.php'] = <<<'PHP'
<?php

namespace App\Filament\Super\Widgets;

use App\Models\Equipment;
use App\Models\Maintenance;
use App\Models\Tenant;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $activeTenants   = Tenant::query()->where('is_active', true)->count();
        $totalTenants    = Tenant::query()->count();
        $totalUsers      = User::query()->whereIn('role', ['admin', 'operative'])->count();
        $totalEquipment  = Equipment::withoutGlobalScopes()->count();
        $totalMaint      = Maintenance::withoutGlobalScopes()->count();

        return [
            Stat::make('Active Tenants', $activeTenants)
                ->description("$totalTenants total registered")
                ->color('success'),
            Stat::make('Platform Users', $totalUsers)
                ->description('Admin + Operative roles')
                ->color('primary'),
            Stat::make('Total Equipment', $totalEquipment)
                ->description('Across all tenants')
                ->color('warning'),
            Stat::make('Total Maintenances', $totalMaint)
                ->description('All historical records')
                ->color('info'),
        ];
    }
}
PHP;

// ─── 3. Escribir archivos ───────────────────────────────────────────────────
foreach ($files as $relPath => $content) {
    $full = $base . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relPath);
    $dir = dirname($full);

    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    file_put_contents($full, $content);
    echo "[OK] Escrito: $relPath\n";
}

echo "\n✅ Super Panel instalado correctamente.\n";
echo "   Accede en: http://localhost:8000/super\n\n";
