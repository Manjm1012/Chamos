<?php

namespace App\Filament\Resources\Equipment\Schemas;

use App\Models\Equipment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EquipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Equipment Information')
                    ->schema([
                        Select::make('type')
                            ->options([
                                Equipment::TYPE_TRUCK => 'Truck',
                                Equipment::TYPE_TRAILER => 'Trailer',
                            ])
                            ->required()
                            ->native(false),
                        TextInput::make('unit_number')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        TextInput::make('brand')
                            ->maxLength(100),
                        TextInput::make('model')
                            ->maxLength(100),
                        Select::make('status')
                            ->options([
                                Equipment::STATUS_ACTIVE => 'Active',
                                Equipment::STATUS_MAINTENANCE => 'In maintenance',
                                Equipment::STATUS_OUT_OF_SERVICE => 'Out of service',
                            ])
                            ->required()
                            ->default(Equipment::STATUS_ACTIVE)
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }
}
