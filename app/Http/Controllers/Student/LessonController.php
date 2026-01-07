<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    /**
     * Display a lesson.
     */
    public function show(Course $course, Lesson $lesson): View
    {
        $user = auth()->user();

        // Verify the lesson belongs to the course
        if ($lesson->module->course_id !== $course->id) {
            abort(404);
        }

        // Check if lesson is available (drip content)
        if (!$lesson->isAvailableFor($user)) {
            $unlockDate = $lesson->module->getUnlockDateFor($user);
            return view('student.lessons.locked', compact('course', 'lesson', 'unlockDate'));
        }

        $lesson->load('module.course');

        // Get navigation info
        $previousLesson = $lesson->getPreviousLesson();
        $nextLesson = $lesson->getNextLesson();

        // Get completion status
        $isCompleted = $user->hasCompletedLesson($lesson);

        // Get course progress
        $progress = $user->getCourseProgress($course);

        // Get sidebar modules with lessons
        $modules = $course->modules()->with('lessons')->ordered()->get();
        $modules->each(function ($module) use ($user) {
            $module->is_available = $module->isAvailableFor($user);
            $module->lessons->each(function ($l) use ($user) {
                $l->is_completed = $user->hasCompletedLesson($l);
            });
        });

        return view('student.lessons.show', compact(
            'course',
            'lesson',
            'modules',
            'previousLesson',
            'nextLesson',
            'isCompleted',
            'progress'
        ));
    }
}










