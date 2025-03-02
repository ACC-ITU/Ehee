<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainSearchRequest;
use App\Http\Requests\VehicleAdvanceSearchRequest;
use Chumputy\Ulhandhu\Facades\Ulhandhu;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Throwable;

class DomainController extends Controller
{
    public function index()
    {
        return Inertia::render('Vehicle/Index');
    }


    public function vehicle(DomainSearchRequest $request)
    {
        try {
            $owner = $request->get('owner');
            $this->logDomainSearch($owner);

            $registries = Cache::remember('registries' . $owner, 60, function () use ($owner) {
                return Ulhandhu::vehicle()
                    ->with(['owners', 'domain', 'information'])
                    ->whereOwner($owner)
                    ->sortBy('registration_no')
                    ->get();
            });

            if (empty($registries->items)) {
                return back()->withErrors('There are no registered vehicles or domains for this owner.');
            }

            $currentOwner = Arr::first($registries->items[0]->owners, function ($owner) use ($request) {
                return Str::lower($owner->ownerDetails->identifier) === Str::lower($request->get('owner'));
            });


            return Inertia::render('Vehicle/Vehicle', [
                'registries' => $registries->items,
                'currentOwner' => $currentOwner,
            ]);

        } catch (Throwable $th) {
            Log::error($th);
            return back()->withErrors($th->getMessage());
        }
    }

    protected function logDomainSearch(string $owner): void
    {
        $user = auth()->user();
        Log::info("Domain Search: User: $user->email (ID: $user->id) initiated a vehicle search by owner $owner");
    }

    public function advanceSearch(VehicleAdvanceSearchRequest $request)
    {
        $filters = $request->validated();
        $query = Ulhandhu::vehicle()
            ->with(['owners', 'domain', 'information']);

        if ($filters['owner']) {
            $query->whereOwner($filters['owner']);
        }
        if ($filters['registration_date']) {
            $query->whereRegistrationAt($filters['registration_date']);
        }

        if ($filters['registration_number']) {
            $query->whereRegistrationNumber($filters['registration_number']);
        }

        try {
            $registries = $query->get();

            return Inertia::render('Vehicle/Vehicle', [
                'registries' => $registries->items,
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return back()->withErrors($th->getMessage());
        }

    }
}
