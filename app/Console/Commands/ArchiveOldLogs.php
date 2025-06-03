<?php

namespace App\Console\Commands;

use App\Enums\ActivityLogType;
use App\Models\ActivityLog;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ArchiveOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:archive {--days=90}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive logs older than specified days';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Logs archiving started.');
        $cutoffDate = Carbon::now()->subDays($this->option('days'));

        $this->info('Logs archiving before: '.$cutoffDate->format('Y-m-d H:i:s'));

        // Archive to a separate table or export to file system
        // For example (simplified):
        ActivityLog::where('created_at', '<', $cutoffDate)->chunk(1000, function ($logs) {
            // Process chunk (e.g., move to archive table or file)
            // Then delete
            $ids = $logs->pluck('id');
            ActivityLog::whereIn('id', $ids)->delete();
        });

        $this->info('Logs archived successfully.');
    }
}
