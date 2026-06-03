<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Maintenance;
use App\Models\MaintenanceItem;
use App\Support\Tenancy\TenantContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMaintenanceRequest extends FormRequest
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
                'sometimes',
                'required',
                'integer',
                Rule::exists('equipment', 'id')->where(fn ($query) => $query->where('tenant_id', $tenantId)),
            ],
            'date' => ['sometimes', 'required', 'date'],
            'type' => ['sometimes', 'required', Rule::in([Maintenance::TYPE_PREVENTIVE, Maintenance::TYPE_CORRECTIVE])],
            'status' => ['sometimes', 'required', Rule::in(array_keys(Maintenance::statusOptions()))],
            'odometer_hours' => ['sometimes', 'required', 'integer', 'min:0'],
            'description' => ['sometimes', 'required', 'string'],
            'cost' => ['sometimes', 'required', 'numeric', 'min:0'],
            'category'                  => ['sometimes', 'nullable', Rule::in(array_keys(MaintenanceItem::categoryLabels()))],
            'service_item'              => ['sometimes', 'nullable', 'string', 'max:150'],
            'equipment_system'          => ['sometimes', 'nullable', Rule::in(['truck', 'trailer', 'reefer', 'both'])],
            'parts_cost'                => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'labor_cost'                => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'vendor'                    => ['sometimes', 'nullable', 'string', 'max:150'],
            'invoice_number'            => ['sometimes', 'nullable', 'string', 'max:80'],
            'warranty_expiry'           => ['sometimes', 'nullable', 'date'],
            'performed_by'              => ['sometimes', 'nullable', 'string', 'max:120'],
            'next_maintenance_date'     => ['nullable', 'date'],
            'next_maintenance_odometer' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
