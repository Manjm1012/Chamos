<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingMaintenancesTable extends TableWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full'; // Asegura fila completa
    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'mt-6', // margen superior grande para separar secciones
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Upcoming Maintenance Alerts')
            ->query(
                Maintenance::query()
                    ->with('equipment')
                    ->upcoming(15, 1000)
                    ->orderBy('next_maintenance_date'),
            )
            ->columns([
                TextColumn::make('equipment.unit_number')
                    ->label('Unit')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->label('Maintenance type'),
                TextColumn::make('next_maintenance_date')
                    ->date()
                    ->label('Next date')
                    ->placeholder('-'),
                TextColumn::make('odometer_hours')
                    ->label('Current odometer')
                    ->numeric(),
                TextColumn::make('next_maintenance_odometer')
                    ->label('Next odometer')
                    ->numeric()
                    ->placeholder('-'),
                TextColumn::make('cost')
                    ->money('USD')
                    ->label('Latest cost'),
            ]);
    }
}
