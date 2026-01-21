<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Services\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ProgressController extends Controller
{
    protected ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Mark a lesson as complete.
     */
    public function markComplete(Request $request, Lesson $lesson): JsonResponse|RedirectResponse
    {
        $user = auth()->user();
        $course = $lesson->module->course;

        // Check if user is enrolled
        if (!$user->isEnrolledIn($course)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Not enrolled'], 403);
            }
            abort(403);
        }

        // Check if lesson is available
        if (!$lesson->isAvailableFor($user)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Lesson not available'], 403);
            }
            abort(403);
        }

        // Mark as complete
        $this->progressService->markLessonComplete($user, $lesson);

        // Check if course is completed
        $courseCompleted = $this->progressService->checkCourseCompletion($user, $course);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'progress' => $user->getCourseProgress($course),
                'course_completed' => $courseCompleted,
                'certificate' => $courseCompleted ? $user->certificates()->where('course_id', $course->id)->first() : null,
            ]);
        }

        $message = $courseCompleted 
            ? 'Congratulations! You have completed the course!' 
            : 'Lesson marked as complete.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Mark a lesson as incomplete.
     */
    public function markIncomplete(Request $request, Lesson $lesson): JsonResponse|RedirectResponse
    {
        $user = auth()->user();
        $course = $lesson->module->course;

        // Check if user is enrolled
        if (!$user->isEnrolledIn($course)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Not enrolled'], 403);
            }
            abort(403);
        }

        // Remove progress
        $this->progressService->markLessonIncomplete($user, $lesson);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'progress' => $user->getCourseProgress($course),
            ]);
        }

        return redirect()->back()->with('success', 'Lesson marked as incomplete.');
    }
}












