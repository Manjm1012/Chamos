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