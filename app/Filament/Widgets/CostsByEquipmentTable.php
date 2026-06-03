<?php

namespace App\Filament\Widgets;

use App\Models\Equipment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class CostsByEquipmentTable extends TableWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full'; // Asegura fila completa
    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'mb-6', // margen inferior más grande para separar secciones
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Cost by Equipment')
            ->query(
                Equipment::query()
                    ->withCount('maintenances')
                    ->withSum('maintenances as total_cost', 'cost')
                    ->orderByDesc('total_cost'),
            )
            ->columns([
                TextColumn::make('unit_number')
                    ->label('Unit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('maintenances_count')
                    ->label('Maintenances')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->label('Total cost')
                    ->money('USD')
                    ->sortable(),
            ])
            ->defaultSort('total_cost', 'desc')
            ->paginated([10, 25, 50]);
    }
}
