<?php

namespace App\Filament\Resources\Equipment\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaintenancesRelationManager extends RelationManager
{
    protected static string $relationship = 'maintenances';

    protected static ?string $title = 'Maintenance History';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->required()
                    ->default(now()),
                Select::make('type')
                    ->options([
                        'preventive' => 'Preventive',
                        'corrective' => 'Corrective',
                    ])
                    ->required()
                    ->native(false),
                TextInput::make('odometer_hours')
                    ->label('Odometer / Hours')
                    ->numeric()
                    ->required(),
                TextInput::make('cost')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                DatePicker::make('next_maintenance_date'),
                TextInput::make('next_maintenance_odometer')
                    ->numeric(),
                TextInput::make('performed_by')
                    ->maxLength(120),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('odometer_hours')
                    ->label('Odometer / Hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cost')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(60),
                TextColumn::make('next_maintenance_date')
                    ->date()
                    ->label('Next date'),
                TextColumn::make('next_maintenance_odometer')
                    ->label('Next odometer')
                    ->numeric(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('date', 'desc');
    }
}
