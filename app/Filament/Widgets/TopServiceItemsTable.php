<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use App\Models\MaintenanceItem;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopServiceItemsTable extends TableWidget
{
    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Top 10 Service Items (All Time)')
            ->query(
                Maintenance::query()
                    ->whereNotNull('service_item')
                    ->selectRaw('service_item, category, COUNT(*) as total_count, SUM(cost) as total_cost')
                    ->groupBy('service_item', 'category')
                    ->orderByDesc('total_count')
                    ->limit(10),
            )
            ->columns([
                TextColumn::make('service_item')
                    ->label('Service Item')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->formatStateUsing(fn ($state) => MaintenanceItem::categoryLabels()[$state] ?? $state),
                TextColumn::make('total_count')
                    ->label('Count')
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->label('Total Spent')
                    ->money('USD')
                    ->sortable(),
            ]);
    }
}
