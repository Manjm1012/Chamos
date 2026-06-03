<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreEquipmentRequest;
use App\Http\Requests\Api\V1\UpdateEquipmentRequest;
use App\Http\Resources\V1\EquipmentResource;
use App\Http\Resources\V1\MaintenanceResource;
use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EquipmentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Equipment::class);

        $equipment = Equipment::query()
            ->withCount('maintenances')
            ->orderBy('unit_number')
            ->paginate($request->integer('per_page', 15));

        return EquipmentResource::collection($equipment);
    }

    public function store(StoreEquipmentRequest $request): EquipmentResource
    {
        $this->authorize('create', Equipment::class);

        $equipment = Equipment::query()->create($request->validated());

        return new EquipmentResource($equipment->loadCount('maintenances'));
    }

    public function show(Equipment $equipment): EquipmentResource
    {
        $this->authorize('view', $equipment);

        $equipment->load([
            'maintenances' => fn ($query) => $query->latest('date')->limit(25),
        ])->loadCount('maintenances');

        return EquipmentResource::make($equipment)->additional([
            'recent_maintenances' => MaintenanceResource::collection($equipment->maintenances),
        ]);
    }

    public function update(UpdateEquipmentRequest $request, Equipment $equipment): EquipmentResource
    {
        $this->authorize('update', $equipment);

        $equipment->update($request->validated());

        return new EquipmentResource($equipment->refresh()->loadCount('maintenances'));
    }

    public function destroy(Equipment $equipment): JsonResponse
    {
        $this->authorize('delete', $equipment);

        $equipment->delete();

        return response()->json(status: 204);
    }
}
