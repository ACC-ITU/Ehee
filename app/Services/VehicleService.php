<?php

namespace App\Services;

use Chumputy\Ulhandhu\DTOs\PaginatedResponse;
use Chumputy\Ulhandhu\Facades\Ulhandhu;
use Illuminate\Support\Facades\Log;

class VehicleService
{
    public function filterVehicles(array $filters): PaginatedResponse
    {
        $this->logDomainSearch($filters);
        return $this->fetchVehicles($filters);
    }

    protected function fetchVehicles(array $filters): PaginatedResponse
    {
        $query = Ulhandhu::vehicle()
            ->with(['owners', 'domain', 'information', 'island', 'atoll']);

        if (isset($filters['owner']) && $filters['owner']) {
            $query->whereOwner($filters['owner']);
        }
        if (isset($filters['registration_date']) && $filters['registration_date']) {
            $query->whereRegistrationAt($filters['registration_date']);
        }

        if (isset($filters['registration_number']) && $filters['registration_number']) {
            $query->whereRegistrationNumber($filters['registration_number']);
        }

        if (isset($filters['atoll']) && $filters['atoll']) {
            $query->whereIsland($filters['atoll']);
        }

        if (isset($filters['island']) && $filters['island']) {
            $query->whereIsland($filters['island']);
        }

        return $query->get();
    }

    protected function logDomainSearch(array $filters): void
    {
        $user = auth()->user();
        Log::info("Vehicle Search: Log Started ======================== ");
        Log::info("Vehicle Search: User: $user->email (ID: $user->id) initiated a vehicle search ");
        Log::info($filters);

        Log::info("Vehicle Search: Log Ended  ************************* ");
    }
}
