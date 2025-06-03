<?php

namespace App\Http\Controllers;

use App\Events\VehicleViewed;
use App\Http\Requests\VehicleAdvanceSearchRequest;
use App\Services\IslandService;
use App\Services\LoggingService;
use App\Services\VehicleService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class VehicleController extends Controller
{
    public function __construct(
        protected VehicleService $vehicleService,
        protected IslandService  $islandService,
        protected LoggingService $loggingService
    )
    {
    }

    public function index(VehicleAdvanceSearchRequest $request)
    {
        $islands = $this->islandService->getIsland();
        $atolls = $this->islandService->getAtolls();

        $queries = collect($request->except('page', 'status', 'registration_date_operator'));
        $allQueriesEmpty = $queries->every(function ($value, $key) {
            return $value === null || $value === '' || $value === 'all';
        });

        $registries = null;
        if (!$allQueriesEmpty) {
        $filters = $request->validated();
            $registries = $this->vehicleService->filterVehicles($filters);
            $this->loggingService->logSearch($request, $registries->items);
        }



        return Inertia::render('Vehicle/Index', [
            'islands' => $islands,
            'atolls' => $atolls,
            'registries' => $registries,
        ]);
    }

    public function show(string $registrationNumber)
    {
        try {
            $registries = $this->vehicleService->find(registrationNumber: $registrationNumber);
            event(new VehicleViewed($registrationNumber, [], $registries->items));

            return Inertia::render('Vehicle/Show', [
                'registries' => $registries,
            ]);
        } catch (Throwable $th) {
            Log::error($th);

            return back()->withErrors($th->getMessage());
        }

    }
}
