<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Enrollment;

class ProgressService
{
    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Mark a lesson as complete.
     */
    public function markLessonComplete(User $user, Lesson $lesson): LessonProgress
    {
        return LessonProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'completed_at' => now(),
            ]
        );
    }

    /**
     * Mark a lesson as incomplete.
     */
    public function markLessonIncomplete(User $user, Lesson $lesson): void
    {
        LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->delete();

        // Also unmark course as completed if it was completed
        $course = $lesson->module->course;
        $enrollment = $user->getEnrollment($course);

        if ($enrollment && $enrollment->completed_at) {
            $enrollment->update(['completed_at' => null]);
        }
    }

    /**
     * Get progress percentage for a course.
     */
    public function getCourseProgress(User $user, Course $course): int
    {
        $totalLessons = $course->lessons()->count('lessons.id');

        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $this->getCompletedLessonsCount($user, $course);

        return (int) round(($completedLessons / $totalLessons) * 100);
    }

    /**
     * Get count of completed lessons in a course.
     */
    public function getCompletedLessonsCount(User $user, Course $course): int
    {
        return LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
            ->count();
    }

    /**
     * Check if course is completed and handle completion.
     */
    public function checkCourseCompletion(User $user, Course $course): bool
    {
        $totalLessons = $course->lessons()->count('lessons.id');
        $completedLessons = $this->getCompletedLessonsCount($user, $course);

        if ($totalLessons === 0 || $completedLessons < $totalLessons) {
            return false;
        }

        // Mark enrollment as completed
        $enrollment = $user->getEnrollment($course);
        if ($enrollment && !$enrollment->completed_at) {
            $enrollment->markCompleted();

            // Generate certificate
            $this->certificateService->generateCertificate($user, $course);
        }

        return true;
    }

    /**
     * Get the last completed lesson for a course.
     */
    public function getLastCompletedLesson(User $user, Course $course): ?Lesson
    {
        $progress = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
            ->latest('completed_at')
            ->first();

        return $progress?->lesson;
    }

    /**
     * Get the next uncompleted lesson for a course.
     */
    public function getNextLesson(User $user, Course $course): ?Lesson
    {
        $completedLessonIds = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
            ->pluck('lesson_id');

        foreach ($course->modules()->ordered()->get() as $module) {
            // Skip locked modules
            if (!$module->isAvailableFor($user)) {
                continue;
            }

            foreach ($module->lessons()->ordered()->get() as $lesson) {
                if (!$completedLessonIds->contains($lesson->id)) {
                    return $lesson;
                }
            }
        }

        return null;
    }

    /**
     * Get completed lessons for a module.
     */
    public function getCompletedLessonsForModule(User $user, $module): array
    {
        return LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $module->lessons()->pluck('id'))
            ->pluck('lesson_id')
            ->toArray();
    }

    /**
     * Get module progress percentage.
     */
    public function getModuleProgress(User $user, $module): int
    {
        $totalLessons = $module->lessons()->count();

        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = count($this->getCompletedLessonsForModule($user, $module));

        return (int) round(($completedLessons / $totalLessons) * 100);
    }
}









