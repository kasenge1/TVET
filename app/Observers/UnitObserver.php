<?php

namespace App\Observers;

use App\Models\Unit;
use App\Services\NotificationService;

class UnitObserver
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Unit "created" event.
     */
    public function created(Unit $unit): void
    {
        // Notify students in the course about new unit
        $this->notificationService->notifyNewUnit($unit);
    }
}
