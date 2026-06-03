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