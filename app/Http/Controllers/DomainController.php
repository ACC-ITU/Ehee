<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainSearchRequest;
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

    protected function logDomainSearch(string $owner): void {
        $user = auth()->user();
        Log::info("Domain Search: User: $user->email (ID: $user->id) initiated a vehicle search by owner $owner");
    }
}
