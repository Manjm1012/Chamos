<?php

namespace App\Filament\Resources\Maintenances\Tables;

use App\Models\Maintenance;
use App\Models\MaintenanceItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MaintenancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('equipment.unit_number')
                    ->label('Unit')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->formatStateUsing(fn ($state) => MaintenanceItem::categoryLabels()[$state] ?? $state)
                    ->color(fn ($state): string => match ($state) {
                        MaintenanceItem::CAT_ENGINE          => 'danger',
                        MaintenanceItem::CAT_EMISSIONS       => 'warning',
                        MaintenanceItem::CAT_BRAKES          => 'danger',
                        MaintenanceItem::CAT_PREVENTIVE      => 'success',
                        MaintenanceItem::CAT_REEFER          => 'info',
                        MaintenanceItem::CAT_TRAILER         => 'primary',
                        MaintenanceItem::CAT_EMERGENCY       => 'danger',
                        default                              => 'gray',
                    })
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('service_item')
                    ->label('Service Item')
                    ->searchable()
                    ->limit(35)
                    ->tooltip(fn ($state) => $state),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'preventive' => 'success',
                        'corrective' => 'danger',
                        default      => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        Maintenance::STATUS_COMPLETED   => 'success',
                        Maintenance::STATUS_IN_PROGRESS => 'warning',
                        Maintenance::STATUS_PENDING     => 'info',
                        Maintenance::STATUS_CANCELLED   => 'danger',
                        default                         => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('cost')
                    ->label('Total Cost')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('vendor')
                    ->label('Vendor')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('—'),

                TextColumn::make('next_maintenance_date')
                    ->label('Next Service')
                    ->date()
                    ->sortable()
                    ->placeholder('—')
                    ->color(fn ($state) => $state && $state < now()->addDays(15) ? 'danger' : null),

                TextColumn::make('odometer_hours')
                    ->label('Odometer')
                    ->numeric(thousandsSeparator: ',')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('performed_by')
                    ->label('Performed By')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Category')
                    ->options(MaintenanceItem::categoryLabels())
                    ->native(false),

                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'preventive' => 'Preventive',
                        'corrective' => 'Corrective',
                    ])
                    ->native(false),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Maintenance::statusOptions())
                    ->native(false),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }
}

