<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleAdvanceSearchRequest;
use App\Services\IslandService;
use App\Services\VehicleService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class VehicleController extends Controller
{
    public function __construct(protected VehicleService $vehicleService, protected IslandService $islandService)
    {
    }

    public function search(VehicleAdvanceSearchRequest $request)
    {
        try {
            $filters = $request->validated();

            $islands = $this->islandService->getIsland();
            $atolls = $this->islandService->getAtolls();
            $registries = $this->vehicleService->filterVehicles($filters);

            return Inertia::render('Vehicle/Index', [
                'islands' => $islands,
                'atolls' => $atolls,
                'registries' => $registries,
            ]);
        } catch (Throwable $th) {
            Log::error($th);

            return back()->withErrors($th->getMessage());
        }
    }


    public function show(string $registrationNumber)
    {
        try {
            $registries = $this->vehicleService->find(registrationNumber: $registrationNumber);

            return Inertia::render('Vehicle/Show', [
                'registries' => $registries,
            ]);
        } catch (Throwable $th) {
            Log::error($th);

            return back()->withErrors($th->getMessage());
        }

    }
}
