<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Equipment;
use App\Support\Tenancy\TenantContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEquipmentRequest extends FormRequest
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
        return [
            'type' => ['required', Rule::in([Equipment::TYPE_TRUCK, Equipment::TYPE_TRAILER])],
            'unit_number' => ['required', 'string', 'max:50', Rule::unique('equipment', 'unit_number')->where('tenant_id', TenantContext::id())],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in([
                Equipment::STATUS_ACTIVE,
                Equipment::STATUS_MAINTENANCE,
                Equipment::STATUS_OUT_OF_SERVICE,
            ])],
        ];
    }
}
