<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Show the form to create a new notification.
     */
    public function create()
    {
        $courses = Course::where('is_published', true)
            ->orderBy('title')
            ->get();

        $icons = [
            'bell' => 'Bell',
            'megaphone' => 'Megaphone',
            'info-circle' => 'Info',
            'exclamation-triangle' => 'Warning',
            'check-circle' => 'Success',
            'star' => 'Star',
            'gift' => 'Gift',
            'calendar-event' => 'Event',
            'book' => 'Book',
            'question-circle' => 'Question',
        ];

        $colors = [
            'primary' => 'Blue',
            'success' => 'Green',
            'warning' => 'Yellow',
            'danger' => 'Red',
            'info' => 'Cyan',
            'secondary' => 'Gray',
        ];

        return view('admin.notifications.create', compact('courses', 'icons', 'colors'));
    }

    /**
     * Send notifications to targeted users.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'icon' => 'required|string|max:50',
            'icon_color' => 'required|string|max:20',
            'target_type' => 'required|in:all,course,admins',
            'course_id' => 'required_if:target_type,course|nullable|exists:courses,id',
            'action_url' => 'nullable|url|max:255',
            'send_email' => 'nullable|boolean',
        ]);

        $targetType = $validated['target_type'];
        $sendEmail = $request->boolean('send_email');
        $sentCount = 0;

        if ($targetType === 'all') {
            // Send to all students (users with student role)
            $users = User::role('student')->get();
            foreach ($users as $user) {
                $this->notificationService->send(
                    $user,
                    Notification::TYPE_SYSTEM,
                    $validated['title'],
                    $validated['message'],
                    $validated['action_url'] ?? null,
                    $validated['icon'],
                    $validated['icon_color'],
                    null,
                    $sendEmail
                );
                $sentCount++;
            }
        } elseif ($targetType === 'course') {
            // Send to all students enrolled in a specific course
            $course = Course::findOrFail($validated['course_id']);
            $students = $course->students;

            foreach ($students as $student) {
                $this->notificationService->send(
                    $student,
                    Notification::TYPE_SYSTEM,
                    $validated['title'],
                    $validated['message'],
                    $validated['action_url'] ?? null,
                    $validated['icon'],
                    $validated['icon_color'],
                    ['course_id' => $course->id, 'course_name' => $course->title],
                    $sendEmail
                );
                $sentCount++;
            }
        } elseif ($targetType === 'admins') {
            // Send to all staff members (admins, content managers, etc.)
            $admins = User::role(['super-admin', 'admin', 'content-manager', 'question-editor'])->get();
            foreach ($admins as $admin) {
                $this->notificationService->send(
                    $admin,
                    Notification::TYPE_SYSTEM,
                    $validated['title'],
                    $validated['message'],
                    $validated['action_url'] ?? null,
                    $validated['icon'],
                    $validated['icon_color'],
                    null,
                    $sendEmail
                );
                $sentCount++;
            }
        }

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', "Notification sent to {$sentCount} user(s) successfully!");
    }

    /**
     * Get student count for a course (AJAX).
     */
    public function getCourseStudentCount(Course $course)
    {
        return response()->json([
            'count' => $course->students()->count(),
            'course_name' => $course->title,
        ]);
    }
}
