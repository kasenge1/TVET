<?php

namespace App\Observers;

use App\Models\Question;
use App\Services\NotificationService;

class QuestionObserver
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Question "created" event.
     */
    public function created(Question $question): void
    {
        // Notify students in the course about new question
        $this->notificationService->notifyNewQuestion($question);
    }
}
