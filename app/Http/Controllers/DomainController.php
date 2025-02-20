<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainSearchRequest;
use Chumputy\Ulhandhu\Facades\Ulhandhu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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

            $registries = Cache::remember('registries' . $owner, 60, function () use ($owner) {
                return Ulhandhu::vehicle()
                    ->with(['owners', 'domain', 'information'])
                    ->whereOwner($owner)
                    ->sortBy('registration_no')
                    ->get();
            });


            if (empty($registries)) {
                return back()->withErrors('There are no registered vehicles or domains for this owner.');
            }

            return Inertia::render('Vehicle/Vehicle', [
                'registries' => $registries->items,
            ]);

        } catch (Throwable $th) {
            Log::error($th);
            return back()->withErrors($th->getMessage());
        }
    }
}
