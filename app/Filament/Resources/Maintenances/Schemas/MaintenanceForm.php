<?php

namespace App\Filament\Resources\Maintenances\Schemas;

use App\Models\Equipment;
use App\Models\Maintenance;
use App\Models\MaintenanceItem;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MaintenanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Service Information')
                ->schema([
                    Select::make('equipment_id')
                        ->label('Equipment')
                        ->options(
                            Equipment::query()
                                ->orderBy('unit_number')
                                ->get()
                                ->mapWithKeys(fn ($e) => [$e->id => "{$e->unit_number} ({$e->type})"])
                        )
                        ->searchable()
                        ->required()
                        ->live(),

                    Select::make('type')
                        ->label('Maintenance Type')
                        ->options([
                            Maintenance::TYPE_PREVENTIVE => 'Preventive',
                            Maintenance::TYPE_CORRECTIVE => 'Corrective',
                        ])
                        ->required()
                        ->native(false),

                    Select::make('category')
                        ->label('System / Category')
                        ->options(MaintenanceItem::categoryLabels())
                        ->required()
                        ->native(false)
                        ->live()
                        ->afterStateUpdated(fn ($set) => $set('service_item', null)),

                    Select::make('service_item')
                        ->label('Service Item')
                        ->options(function (Get $get) {
                            $category = $get('category');
                            $equipmentId = $get('equipment_id');

                            if (! $category) {
                                return [];
                            }

                            $equipmentType = null;
                            if ($equipmentId) {
                                $equipment = Equipment::find($equipmentId);
                                $equipmentType = $equipment?->type;
                            }

                            return MaintenanceItem::optionsForCategory($category, $equipmentType);
                        })
                        ->searchable()
                        ->required()
                        ->native(false)
                        ->helperText('Select a category first to filter items.'),

                    Select::make('equipment_system')
                        ->label('Sub-System Affected')
                        ->options([
                            'truck'   => 'Truck',
                            'trailer' => 'Trailer',
                            'reefer'  => 'Reefer Unit',
                            'both'    => 'Truck + Trailer',
                        ])
                        ->native(false)
                        ->nullable(),

                    Select::make('status')
                        ->label('Status')
                        ->options(Maintenance::statusOptions())
                        ->required()
                        ->default(Maintenance::STATUS_PENDING)
                        ->native(false),

                    DatePicker::make('date')
                        ->label('Service Date')
                        ->required(),

                    TextInput::make('odometer_hours')
                        ->label('Odometer / Hours')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->suffix('mi / hrs'),
                ])
                ->columns(2),

            Section::make('Cost Breakdown')
                ->schema([
                    TextInput::make('parts_cost')
                        ->label('Parts Cost')
                        ->numeric()
                        ->minValue(0)
                        ->prefix('$')
                        ->default(0)
                        ->live()
                        ->afterStateUpdated(function ($state, $set, Get $get) {
                            $parts  = (float) ($state ?? 0);
                            $labor  = (float) ($get('labor_cost') ?? 0);
                            $set('cost', round($parts + $labor, 2));
                        }),

                    TextInput::make('labor_cost')
                        ->label('Labor Cost')
                        ->numeric()
                        ->minValue(0)
                        ->prefix('$')
                        ->default(0)
                        ->live()
                        ->afterStateUpdated(function ($state, $set, Get $get) {
                            $parts  = (float) ($get('parts_cost') ?? 0);
                            $labor  = (float) ($state ?? 0);
                            $set('cost', round($parts + $labor, 2));
                        }),

                    TextInput::make('cost')
                        ->label('Total Cost')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->prefix('$')
                        ->default(0)
                        ->helperText('Auto-calculated from Parts + Labor. Editable manually.'),
                ])
                ->columns(3),

            Section::make('Vendor & Documentation')
                ->schema([
                    TextInput::make('performed_by')
                        ->label('Performed By')
                        ->maxLength(120),

                    TextInput::make('vendor')
                        ->label('Vendor / Shop')
                        ->maxLength(150),

                    TextInput::make('invoice_number')
                        ->label('Invoice #')
                        ->maxLength(80),

                    DatePicker::make('warranty_expiry')
                        ->label('Warranty Expiry'),
                ])
                ->columns(2),

            Section::make('Work Description')
                ->schema([
                    Textarea::make('description')
                        ->label('Work Performed')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Section::make('Next Service Schedule')
                ->schema([
                    DatePicker::make('next_maintenance_date')
                        ->label('Next Service Date'),

                    TextInput::make('next_maintenance_odometer')
                        ->label('Next Service Odometer')
                        ->numeric()
                        ->minValue(0)
                        ->suffix('mi / hrs'),
                ])
                ->columns(2),
        ]);
    }
}

