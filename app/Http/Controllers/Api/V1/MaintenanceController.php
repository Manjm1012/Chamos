<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreMaintenanceRequest;
use App\Http\Requests\Api\V1\UpdateMaintenanceRequest;
use App\Http\Resources\V1\MaintenanceResource;
use App\Models\Equipment;
use App\Models\Maintenance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MaintenanceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Maintenance::class);

        $query = Maintenance::query()->with('equipment');

        if ($request->filled('type')) {
            $query->where('type', $request->string('type')->toString());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('equipment_id')) {
            $query->where('equipment_id', $request->integer('equipment_id'));
        }

        if ($request->boolean('upcoming')) {
            $query->upcoming();
        }

        if ($request->boolean('recent')) {
            $query->recent();
        }

        return MaintenanceResource::collection(
            $query
                ->latest('date')
                ->paginate($request->integer('per_page', 15)),
        );
    }

    public function byEquipment(Request $request, Equipment $equipment): AnonymousResourceCollection
    {
        $this->authorize('view', $equipment);

        $records = $equipment->maintenances()
            ->with('equipment')
            ->latest('date')
            ->paginate($request->integer('per_page', 15));

        return MaintenanceResource::collection($records);
    }

    public function store(StoreMaintenanceRequest $request): MaintenanceResource
    {
        $this->authorize('create', Maintenance::class);

        $maintenance = Maintenance::query()->create($request->validated());

        return new MaintenanceResource($maintenance->load('equipment'));
    }

    public function show(Maintenance $maintenance): MaintenanceResource
    {
        $this->authorize('view', $maintenance);

        return new MaintenanceResource($maintenance->load('equipment'));
    }

    public function update(UpdateMaintenanceRequest $request, Maintenance $maintenance): MaintenanceResource
    {
        $this->authorize('update', $maintenance);

        $maintenance->update($request->validated());

        return new MaintenanceResource($maintenance->refresh()->load('equipment'));
    }

    public function destroy(Maintenance $maintenance): JsonResponse
    {
        $this->authorize('delete', $maintenance);

        $maintenance->delete();

        return response()->json(status: 204);
    }
}
