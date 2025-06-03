<?php

namespace App\Http\Controllers;

use App\Enums\ActivityLogType;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Parse filters
        $dateRange = $request->input('date_range', 'week');
        $type = $request->input('type', 'all');

        // Set date range
        $startDate = $this->getStartDate($dateRange, $request);
        $endDate = $request->input('end_date', now()->toDateString());

        // Base query
        $query = ActivityLog::query()
            ->when($startDate, function ($q) use ($startDate) {
                return $q->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                return $q->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
            });

        // Get search data
        $searchQuery = clone $query;
        $searchData = $this->getSearchData($searchQuery, $type, $startDate, $endDate);

        // Get view data
        $viewQuery = clone $query;
        $viewData = $this->getViewData($viewQuery, $type, $startDate, $endDate);

        // Get common search parameters
        $commonParamsQuery = clone $query;
        $commonSearchParams = $this->getCommonSearchParams($commonParamsQuery, $startDate, $endDate);

        // Get popular vehicles
        $popularVehiclesQuery = clone $query;
        $popularVehicles = $this->getPopularVehicles($popularVehiclesQuery, $startDate, $endDate);

        return Inertia::render('Admin/Analytics/Dashboard', [
            'searchData' => $searchData,
            'viewData' => $viewData,
            'commonSearchParams' => $commonSearchParams,
            'popularVehicles' => $popularVehicles,
        ]);
    }

    protected function getSearchData($query, $type, $startDate, $endDate)
    {
        if ($type === 'view') {
            return [];
        }

        // Determine grouping format based on date range
        list($sqliteFormat, $displayFormat) = $this->getGroupFormat($startDate, $endDate);

        return $query->where('action_type', 'search')
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->get()
            ->map(function ($log) use ($sqliteFormat) {
                // Format the date string according to the grouping format
                $date = Carbon::parse($log->created_at);
                if ($sqliteFormat === 'day') {
                    return [
                        'date' => $date->format('Y-m-d'),
                        'formatted_date' => $date->format('M d'),
                    ];
                } elseif ($sqliteFormat === 'week') {
                    return [
                        'date' => $date->format('Y') . '-' . $date->format('W'),
                        'formatted_date' => 'W' . $date->format('W') . ', ' . $date->format('Y'),
                    ];
                } else { // month
                    return [
                        'date' => $date->format('Y-m'),
                        'formatted_date' => $date->format('M Y'),
                    ];
                }
            })
            ->groupBy('date')
            ->map(function ($group) {
                return [
                    'date' => $group->first()['formatted_date'],
                    'count' => $group->count(),
                ];
            })
            ->values()
            ->sortBy(function ($item) {
                return $item['date'];
            })
            ->values();
    }

    protected function getViewData($query, $type, $startDate, $endDate)
    {
        if ($type === 'search') {
            return [];
        }

        // Determine grouping format based on date range
        list($sqliteFormat, $displayFormat) = $this->getGroupFormat($startDate, $endDate);

        return $query->where('action_type', 'view')
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->get()
            ->map(function ($log) use ($sqliteFormat) {
                // Format the date string according to the grouping format
                $date = Carbon::parse($log->created_at);
                if ($sqliteFormat === 'day') {
                    return [
                        'date' => $date->format('Y-m-d'),
                        'formatted_date' => $date->format('M d'),
                    ];
                } elseif ($sqliteFormat === 'week') {
                    return [
                        'date' => $date->format('Y') . '-' . $date->format('W'),
                        'formatted_date' => 'W' . $date->format('W') . ', ' . $date->format('Y'),
                    ];
                } else { // month
                    return [
                        'date' => $date->format('Y-m'),
                        'formatted_date' => $date->format('M Y'),
                    ];
                }
            })
            ->groupBy('date')
            ->map(function ($group) {
                return [
                    'date' => $group->first()['formatted_date'],
                    'count' => $group->count(),
                ];
            })
            ->values()
            ->sortBy(function ($item) {
                return $item['date'];
            })
            ->values();
    }

    protected function getCommonSearchParams($query, $startDate, $endDate)
    {
        // Extract the top search parameters from search logs
        return $query->where('action_type', ActivityLogType::SEARCH)
            ->whereNotNull('search_parameters')
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->get()
            ->map(function ($log) {
                return [
                    'search_parameters' => $log->search_parameters ?? null,
                    'date' => $log->created_at->format('M d, Y'),
                    'count' => 1 // We'll aggregate this later
                ];
            })
            ->groupBy('search_parameters')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'search_parameters' => $first['search_parameters'],
                    'date' => $first['date'],
                    'count' => $group->count()
                ];
            })
            ->values()
            ->sortByDesc('count')
            ->take(10)
            ->values()
            ->all();
    }

    protected function getPopularVehicles($query, $startDate, $endDate)
    {
        return $query->where('action_type', ActivityLogType::VIEW)
            ->whereNotNull('registration_no')
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->selectRaw('registration_no, COUNT(*) as view_count, MAX(created_at) as last_viewed_at')
            ->groupBy('registration_no')
            ->orderByDesc('view_count')
            ->limit(10)
            ->get();
    }

    protected function getGroupFormat($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $diffInDays = $start->diffInDays($end);

        if ($diffInDays <= 31) {
            // Daily grouping for periods up to a month
            return ['day', 'M d'];
        } elseif ($diffInDays <= 90) {
            // Weekly grouping for periods up to 3 months
            return ['week', 'W W, Y'];
        } else {
            // Monthly grouping for longer periods
            return ['month', 'M Y'];
        }
    }

    protected function getStartDate($dateRange, Request $request)
    {
        switch ($dateRange) {
            case 'day':
                return now()->startOfDay();
            case 'month':
                return now()->subMonth();
            case 'quarter':
                return now()->subQuarter();
            case 'year':
                return now()->subYear();
            case 'custom':
                return $request->input('start_date');
            default:
                return now()->subWeek();
        }
    }


    public function exportData(Request $request)
    {
        // Parse filters
        $dateRange = $request->input('date_range', 'week');
        $type = $request->input('type', ActivityLogType::SEARCH);

        // Set date range
        $startDate = $this->getStartDate($dateRange, $request);
        $endDate = $request->input('end_date', now()->toDateString());

        // Base query
        $query = ActivityLog::query()
            ->when($startDate, function ($q) use ($startDate) {
                return $q->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                return $q->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
            })
            ->when($type !== 'all', function ($q) use ($type) {
                return $q->where('action_type', $type);
            })
            ->with('user')
            ->orderBy('created_at', 'desc');

        $logs = $query->get();

        // Generate CSV
        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'ID', 'Type', 'User', 'Registration No.',
                'Search Parameters', 'Domain', 'IP Address', 'Timestamp'
            ]);

            // Add data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->action_type,
                    $log->user?->name ?? 'Unknown',
                    $log->registration_no ?? '-',
                    json_encode($log->search_parameters),
                    $log->domain ?? '-',
                    $log->ip_address ?? '-',
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
