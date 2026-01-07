<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $stats = [
            'total_courses' => Course::count(),
            'published_courses' => Course::published()->count(),
            'total_students' => User::students()->count(),
            'total_enrollments' => Enrollment::count(),
            'certificates_issued' => Certificate::count(),
        ];

        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->latest('enrolled_at')
            ->take(10)
            ->get();

        $popularCourses = Course::withCount('enrollments')
            ->published()
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentEnrollments', 'popularCourses'));
    }

    /**
     * Display list of students.
     */
    public function students(Request $request): View
    {
        $query = User::students()->withCount('enrollments');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(20);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Display a specific student.
     */
    public function showStudent(User $user): View
    {
        $user->load(['enrollments.course', 'certificates.course']);

        $enrollments = $user->enrollments()
            ->with('course')
            ->latest('enrolled_at')
            ->get();

        return view('admin.students.show', compact('user', 'enrollments'));
    }
}











