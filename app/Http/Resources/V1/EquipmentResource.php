<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'type' => $this->type,
            'unit_number' => $this->unit_number,
            'brand' => $this->brand,
            'model' => $this->model,
            'status' => $this->status,
            'maintenances_count' => $this->whenCounted('maintenances'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
