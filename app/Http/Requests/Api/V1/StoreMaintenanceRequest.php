<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Maintenance;
use App\Models\MaintenanceItem;
use App\Support\Tenancy\TenantContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $tenantId = TenantContext::id();

        return [
            'equipment_id' => [
                'required',
                'integer',
                Rule::exists('equipment', 'id')->where(fn ($query) => $query->where('tenant_id', $tenantId)),
            ],
            'date' => ['required', 'date'],
            'type' => ['required', Rule::in([Maintenance::TYPE_PREVENTIVE, Maintenance::TYPE_CORRECTIVE])],
            'status' => ['nullable', Rule::in(array_keys(Maintenance::statusOptions()))],
            'odometer_hours' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
            'category'                  => ['nullable', Rule::in(array_keys(MaintenanceItem::categoryLabels()))],
            'service_item'              => ['nullable', 'string', 'max:150'],
            'equipment_system'          => ['nullable', Rule::in(['truck', 'trailer', 'reefer', 'both'])],
            'parts_cost'                => ['nullable', 'numeric', 'min:0'],
            'labor_cost'                => ['nullable', 'numeric', 'min:0'],
            'vendor'                    => ['nullable', 'string', 'max:150'],
            'invoice_number'            => ['nullable', 'string', 'max:80'],
            'warranty_expiry'           => ['nullable', 'date'],
            'performed_by'              => ['nullable', 'string', 'max:120'],
            'next_maintenance_date'     => ['nullable', 'date'],
            'next_maintenance_odometer' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
