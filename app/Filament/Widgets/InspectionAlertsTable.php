<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class InspectionAlertsTable extends TableWidget
{
    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        $inspectionItems = [
            'Federal Annual Inspection',
            'DOT Inspection',
            'Pre-Trip Inspection',
        ];

        return $table
            ->heading('⚠️ Upcoming Inspections & DOT Alerts (30 days)')
            ->query(
                Maintenance::query()
                    ->with('equipment')
                    ->whereIn('service_item', $inspectionItems)
                    ->whereNotNull('next_maintenance_date')
                    ->where('next_maintenance_date', '<=', now()->addDays(30)->toDateString())
                    ->where('next_maintenance_date', '>=', now()->toDateString())
                    ->orderBy('next_maintenance_date'),
            )
            ->columns([
                TextColumn::make('equipment.unit_number')
                    ->label('Unit')
                    ->searchable(),
                TextColumn::make('service_item')
                    ->label('Inspection Type'),
                TextColumn::make('next_maintenance_date')
                    ->label('Due Date')
                    ->date()
                    ->sortable()
                    ->color(fn ($state) => $state && $state <= now()->addDays(7) ? 'danger' : 'warning'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ]);
    }
}
