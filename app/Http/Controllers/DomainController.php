<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainSearchRequest;
use Chumputy\Ulhandhu\Facades\Ulhandhu;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DomainController extends Controller
{
    public function index(DomainSearchRequest $request)
    {
        $domains = [];
        $params = '';
        try {
            $query = Ulhandhu::domain()->includes('owner');

            if ($request->has('owner')) {
                $query->whereOwner($request->input('owner'));
                $domains = $query->get();
                $params = 'owner';
            }

            return Inertia::render('Vehicle/Index', [
                'domains' => $domains,
                'params' => $params
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withErrors($e->getMessage());
        }
    }
}
