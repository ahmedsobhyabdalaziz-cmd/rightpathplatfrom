<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display student dashboard.
     */
    public function dashboard(): View
    {
        $user = auth()->user();

        $enrollments = $user->enrollments()
            ->with('course')
            ->latest('enrolled_at')
            ->get();

        // Calculate stats
        $stats = [
            'total_courses' => $enrollments->count(),
            'completed_courses' => $enrollments->where('completed_at', '!=', null)->count(),
            'in_progress' => $enrollments->where('completed_at', null)->count(),
            'certificates' => $user->certificates()->count(),
        ];

        // Get in-progress courses with progress
        $inProgressCourses = $enrollments->filter(function ($enrollment) {
            return !$enrollment->isCompleted();
        })->map(function ($enrollment) use ($user) {
            $enrollment->progress_percent = $user->getCourseProgress($enrollment->course);
            $enrollment->next_lesson = $this->getNextLesson($user, $enrollment->course);
            return $enrollment;
        });

        return view('student.dashboard', compact('stats', 'inProgressCourses', 'enrollments'));
    }

    /**
     * Display all courses available to student.
     */
    public function index(): View
    {
        $user = auth()->user();

        $enrolledCourseIds = $user->enrollments()->pluck('course_id');

        $availableCourses = Course::published()
            ->whereNotIn('id', $enrolledCourseIds)
            ->withCount('modules')
            ->latest()
            ->paginate(12);

        $enrolledCourses = $user->enrollments()
            ->with('course')
            ->get()
            ->map(function ($enrollment) use ($user) {
                $enrollment->progress_percent = $user->getCourseProgress($enrollment->course);
                return $enrollment;
            });

        return view('student.courses.index', compact('availableCourses', 'enrolledCourses'));
    }

    /**
     * Enroll in a course.
     */
    public function enroll(Course $course): RedirectResponse
    {
        $user = auth()->user();

        if (!$course->is_published) {
            abort(404);
        }

        if ($user->isEnrolledIn($course)) {
            return redirect()
                ->route('student.courses.show', $course)
                ->with('info', 'You are already enrolled in this course.');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        return redirect()
            ->route('student.courses.show', $course)
            ->with('success', 'Successfully enrolled in the course!');
    }

    /**
     * Display enrolled course with curriculum.
     */
    public function show(Course $course): View
    {
        $user = auth()->user();
        $enrollment = $user->getEnrollment($course);

        $course->load(['modules.lessons']);

        // Add availability info to each module and lesson
        $modules = $course->modules->map(function ($module) use ($user) {
            $module->is_available = $module->isAvailableFor($user);
            $module->unlock_date = $module->getUnlockDateFor($user);
            
            $module->lessons->each(function ($lesson) use ($user) {
                $lesson->is_completed = $user->hasCompletedLesson($lesson);
                $lesson->is_available = $lesson->isAvailableFor($user);
            });

            return $module;
        });

        $progress = $user->getCourseProgress($course);
        $nextLesson = $this->getNextLesson($user, $course);

        return view('student.courses.show', compact('course', 'modules', 'enrollment', 'progress', 'nextLesson'));
    }

    /**
     * Get the next uncompleted lesson for a user in a course.
     */
    protected function getNextLesson($user, Course $course)
    {
        foreach ($course->modules as $module) {
            if (!$module->isAvailableFor($user)) {
                continue;
            }

            foreach ($module->lessons as $lesson) {
                if (!$user->hasCompletedLesson($lesson)) {
                    return $lesson;
                }
            }
        }

        return null;
    }
}










