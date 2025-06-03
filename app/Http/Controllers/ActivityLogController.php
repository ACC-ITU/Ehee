<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Process filters
        $filters = $this->processFilters($request);

        // Build query with filters
        $query = $this->buildFilteredQuery($filters);

        // Get paginated results
        $logs = $query->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Load reference data
        $users = User::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Admin/Analytics/Index', [
            'logs' => $logs,
            'users' => $users,
            'filters' => $filters
        ]);
    }

    protected function processFilters(Request $request)
    {
        return [
            'userId' => $request->input('userId', 'all'),
            'actionType' => $request->input('actionType', 'all'),
            'dateRange' => $request->input('dateRange', 'week'),
            'startDate' => $request->input('startDate', now()->subWeek()->toDateString()),
            'endDate' => $request->input('endDate', now()->toDateString()),
            'searchText' => $request->input('searchText'),
            'registrationNo' => $request->input('registrationNo'),
            'domainId' => $request->input('domainId', 'all'),
            'ipAddress' => $request->input('ipAddress')
        ];
    }

    protected function buildFilteredQuery(array $filters)
    {
        $query = ActivityLog::query();

        // Apply user filter
        if ($filters['userId'] !== 'all') {
            $query->where('user_id', $filters['userId']);
        }

        // Apply action type filter
        if ($filters['actionType'] !== 'all') {
            $query->where('action_type', $filters['actionType']);
        }

        // Apply date range
        $dateStart = null;
        $dateEnd = now()->endOfDay();

        switch ($filters['dateRange']) {
            case 'today':
                $dateStart = now()->startOfDay();
                break;
            case 'yesterday':
                $dateStart = now()->subDay()->startOfDay();
                $dateEnd = now()->subDay()->endOfDay();
                break;
            case 'week':
                $dateStart = now()->subWeek();
                break;
            case 'month':
                $dateStart = now()->subMonth();
                break;
            case 'custom':
                $dateStart = $filters['startDate'] ? Carbon::parse($filters['startDate']) : null;
                $dateEnd = $filters['endDate'] ? Carbon::parse($filters['endDate'])->endOfDay() : now()->endOfDay();
                break;
            case 'all':
                // No date filter
                break;
        }

        if ($dateStart) {
            $query->where('created_at', '>=', $dateStart);
        }

        if ($dateEnd) {
            $query->where('created_at', '<=', $dateEnd);
        }

        // Apply registration number filter
        if (!empty($filters['registrationNo'])) {
            $query->where('registration_no', 'like', '%' . $filters['registrationNo'] . '%');
        }

        // Apply IP address filter
        if (!empty($filters['ipAddress'])) {
            $query->where('ip_address', 'like', '%' . $filters['ipAddress'] . '%');
        }

        // Apply search text (across multiple fields)
        if (!empty($filters['searchText'])) {
            $query->where(function ($q) use ($filters) {
                $searchTerm = '%' . $filters['searchText'] . '%';

                $q->where('registration_no', 'like', $searchTerm)
                    ->orWhere('ip_address', 'like', $searchTerm)
                    ->orWhere(function ($subQ) use ($searchTerm) {
                        $subQ->whereRaw('LOWER(JSON_EXTRACT(search_parameters, "$")) LIKE ?', [strtolower($searchTerm)]);
                    });
            });
        }

        return $query;
    }

    public function export(Request $request)
    {
        // Process filters
        $filters = $this->processFilters($request);

        // Build query with filters
        $query = $this->buildFilteredQuery($filters);

        // Get all filtered logs
        $logs = $query->with('user')->orderBy('created_at', 'desc')->get();

        // Set up file name and headers
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "activity_logs_{$timestamp}.csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        // Create CSV
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Date & Time',
                'User',
                'Type',
                'Registration No.',
                'Domain',
                'IP Address',
                'Endpoint',
                'Search Parameters'
            ]);

            // Add log data
            foreach ($logs as $log) {

                fputcsv($file, [
                    $log->id,
                    $log->created_at,
                    $log->user ? $log->user->name : 'Unknown',
                    $log->action_type,
                    $log->registration_no ?? '',
                    $log->ip_address ?? '',
                    $log->endpoint ?? '',
                    is_array($log->search_parameters)
                        ? json_encode($log->search_parameters)
                        : $log->search_parameters
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
