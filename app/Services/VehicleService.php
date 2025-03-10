<?php

namespace App\Services;

use Chumputy\Ulhandhu\DTOs\PaginatedResponse;
use Chumputy\Ulhandhu\Facades\Ulhandhu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class VehicleService
{

    public function find(string $registrationNumber)
    {
        return Cache::remember("vehicle_find_$registrationNumber", now()->addMinutes(20), function () use ($registrationNumber) {
            return Ulhandhu::vehicle()
                ->with(['owners', 'domain', 'information', 'island', 'atoll'])
                ->whereRegistrationNumber($registrationNumber)
                ->get();
        });
    }

    public function filterVehicles(array $filters): PaginatedResponse
    {
        $this->logDomainSearch($filters);
        return $this->fetchVehicles($filters);
    }

    protected function fetchVehicles(array $filters): PaginatedResponse
    {
        $cacheKey = 'api_response_' . md5(json_encode($filters));
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($filters) {
            $query = Ulhandhu::vehicle()
                ->with(['owners', 'domain', 'information', 'island', 'atoll'])
                ->sortBy('registration_at', 'desc');

            if (isset($filters['page']) && $filters['page']) {
                $query->page($filters['page']);
            }

            if (isset($filters['owner']) && $filters['owner']) {
                $query->whereOwner($filters['owner']);
            }
            if (isset($filters['registration_date']) && $filters['registration_date']) {
                if (isset($filters['registration_date_operator']) && $filters['registration_date_operator']) {
                    $query->whereRegistrationAt($filters['registration_date'], $filters['registration_date_operator']);
                } else {
                    $query->whereRegistrationAt($filters['registration_date']);
                }
            }

            if (isset($filters['registration_number']) && $filters['registration_number']) {
                $query->whereRegistrationNumber($filters['registration_number']);
            }

            if (isset($filters['domain']) && $filters['domain']) {
                $query->whereDomain($filters['domain']);
            }

            if (isset($filters['atoll']) && $filters['atoll']) {
                $query->whereAtoll($filters['atoll']);
            }

            if (isset($filters['island']) && $filters['island']) {
                $query->whereIsland($filters['island']);
            }

            if (isset($filters['status']) && $filters['status'] == 1) {
                $query->active();
            }

            if (isset($filters['status']) && $filters['status'] == 0) {
                $query->inActive();
            }

            return $query->get();
        });


    }

    protected function logDomainSearch(array $filters): void
    {
        $user = auth()->user();
        Log::info("\n\n");
        Log::info("Vehicle Search: Log Started ======================== ");
        Log::info("Vehicle Search: User: $user->email (ID: $user->id) initiated a vehicle search ");
        Log::info($filters);

        Log::info("Vehicle Search: Log Ended  *************************\n\n");
    }
}
