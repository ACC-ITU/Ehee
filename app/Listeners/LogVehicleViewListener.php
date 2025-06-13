<?php

namespace App\Listeners;

use App\Events\VehicleViewed;
use App\Services\LoggingService;

class LogVehicleViewListener
{
    /**
     * Create the event listener.
     */
    public function __construct(protected LoggingService $loggingService) {}

    /**
     * Handle the event.
     */
    public function handle(VehicleViewed $event): void
    {
        $this->loggingService->logVehicleView($event->registrationNo, $event->search_parameters, $event->metadata);
    }
}
