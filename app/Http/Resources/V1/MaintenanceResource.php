<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'equipment_id' => $this->equipment_id,
            'equipment_unit' => $this->whenLoaded('equipment', fn (): ?string => $this->equipment?->unit_number),
            'date' => $this->date,
            'type' => $this->type,
            'status' => $this->status,
            'odometer_hours' => $this->odometer_hours,
            'description' => $this->description,
            'cost' => $this->cost,
            'performed_by' => $this->performed_by,
            'next_maintenance_date' => $this->next_maintenance_date,
            'next_maintenance_odometer' => $this->next_maintenance_odometer,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
