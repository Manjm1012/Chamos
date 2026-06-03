<?php

namespace App\Filament\Resources\Equipment\Tables;

use App\Filament\Resources\Equipment\EquipmentResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EquipmentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('unit_number')
                    ->label('Unit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'maintenance' => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('brand')
                    ->toggleable(),
                TextColumn::make('model')
                    ->toggleable(),
                TextColumn::make('maintenances_count')
                    ->counts('maintenances')
                    ->label('Maintenances')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->label('Updated')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'truck' => 'Truck',
                        'trailer' => 'Trailer',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'maintenance' => 'In maintenance',
                        'out_of_service' => 'Out of service',
                    ]),
            ])
            ->recordActions([
                Action::make('history')
                    ->label('View history')
                    ->icon('heroicon-o-clock')
                    ->url(fn ($record): string => EquipmentResource::getUrl('history', ['record' => $record])),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('unit_number');
    }
}
