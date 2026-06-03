<?php

namespace App\Filament\Resources\Equipment\Pages;

use App\Filament\Resources\Equipment\EquipmentResource;
use App\Models\Maintenance;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ViewEquipmentHistory extends Page
{
    use InteractsWithRecord;

    protected static string $resource = EquipmentResource::class;

    protected string $view = 'filament.resources.equipment.pages.view-equipment-history';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);

        abort_unless(static::getResource()::canView($this->getRecord()), 403);
    }

    public function getTitle(): string
    {
        return 'Historial del equipo: ' . $this->getRecord()->unit_number;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getViewData(): array
    {
        $record = $this->getRecord();

        $maintenances = $record
            ->maintenances()
            ->latest('date')
            ->get()
            ->values()
            ->map(fn ($maintenance) => $this->decorateMaintenanceStatus($maintenance));

        $summary = [
            'total' => $maintenances->count(),
            'completed' => $maintenances->where('workflow_status', Maintenance::STATUS_COMPLETED)->count(),
            'in_progress' => $maintenances->where('workflow_status', Maintenance::STATUS_IN_PROGRESS)->count(),
            'pending' => $maintenances->where('workflow_status', Maintenance::STATUS_PENDING)->count(),
            'cancelled' => $maintenances->where('workflow_status', Maintenance::STATUS_CANCELLED)->count(),
        ];

        $maintenancesByMonth = $maintenances
            ->groupBy(fn ($maintenance) => $maintenance->date->translatedFormat('F Y'));

        return [
            'record' => $record,
            'summary' => $summary,
            'maintenancesByMonth' => $maintenancesByMonth,
        ];
    }

    private function decorateMaintenanceStatus(object $maintenance): object
    {
        $status = $maintenance->status ?: Maintenance::STATUS_PENDING;

        $statusLabel = match ($status) {
            Maintenance::STATUS_COMPLETED => 'Completado',
            Maintenance::STATUS_IN_PROGRESS => 'En proceso',
            Maintenance::STATUS_CANCELLED => 'Cancelado',
            default => 'Pendiente',
        };

        $maintenance->setAttribute('workflow_status', $status);
        $maintenance->setAttribute('workflow_status_label', $statusLabel);

        return $maintenance;
    }
}
