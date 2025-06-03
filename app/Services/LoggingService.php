<?php

namespace App\Services;

use App\Enums\ActivityLogType;
use App\Http\Requests\VehicleAdvanceSearchRequest;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoggingService
{
    /**
     * Log a vehicle/vessel search
     */
    public function logSearch(VehicleAdvanceSearchRequest $request, array $responseMetadata = null): ActivityLog
    {
        $queriesWithAValue = collect($request->except('page', 'status', 'registration_date_operator'))->filter(function ($value, $key) {
            return $value !== null;
        });
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action_type' => ActivityLogType::SEARCH,
            'search_parameters' => $queriesWithAValue,
            'endpoint' => $request->path(),
            'ip_address' => $request->ip(),
            'metadata' => $responseMetadata
        ]);
    }

    /**
     * Log a vehicle/vessel detail view
     */
    public function logVehicleView(string $registrationNo, ?array $searchParameters, ?array $responseMetadata): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action_type' => ActivityLogType::VIEW,
            'registration_no' => $registrationNo,
            'search_parameters' => $searchParameters,
            'ip_address' => request()->ip(),
            'metadata' => $responseMetadata
        ]);
    }
}
