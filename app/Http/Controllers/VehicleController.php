<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleAdvanceSearchRequest;
use App\Services\IslandService;
use App\Services\VehicleService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Throwable;

class VehicleController extends Controller
{
    public function __construct(protected VehicleService $vehicleService)
    {
    }

    public function index(IslandService $islandService)
    {
        try {
            $islands = $islandService->getIsland();
            $atolls = $islandService->getAtolls();

            return Inertia::render('Vehicle/Index', [
                'islands' => $islands,
                'atolls' => $atolls,

            ]);
        } catch (Throwable $e) {
            Log::error($e);

            return Inertia::render('Vehicle/Index')->with(['errors' => ['Unexpected error occurred']]);
        }
    }


    public function advanceSearch(VehicleAdvanceSearchRequest $request)
    {

        try {
            $filters = $request->validated();
            $currentOwner = null;

            $registries = $this->vehicleService->filterVehicles($filters);

            if (empty($registries->items)) {
                return back()->withErrors('No Results found.');
            }

            if (isset($filters['owner']) && $filters['owner']) {
                $currentOwner = Arr::first($registries->items[0]->owners, function ($owner) use ($request) {
                    return Str::lower($owner->ownerDetails->identifier) === Str::lower($request->get('owner'));
                });
            }

            return Inertia::render('Vehicle/Vehicle', [
                'registries' => $registries->items,
                'currentOwner' => $currentOwner,
            ]);
        } catch (Throwable $th) {
            Log::error($th);

            return back()->withErrors($th->getMessage());
        }

    }
}
