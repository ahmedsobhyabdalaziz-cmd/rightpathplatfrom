<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(): View
    {
        $featuredCourses = Course::published()
            ->withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->take(6)
            ->get();

        return view('home', compact('featuredCourses'));
    }

    /**
     * Display public course listing.
     */
    public function courses(Request $request): View
    {
        $query = Course::published()->withCount(['modules', 'enrollments']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('difficulty') && $request->difficulty !== 'all') {
            $query->difficulty($request->difficulty);
        }

        $courses = $query->latest()->paginate(12);

        return view('courses.index', compact('courses'));
    }

    /**
     * Display a public course page.
     */
    public function showCourse(Course $course): View
    {
        if (!$course->is_published && (!auth()->check() || !auth()->user()->isAdmin())) {
            abort(404);
        }

        $course->load(['modules.lessons']);

        $isEnrolled = auth()->check() && auth()->user()->isEnrolledIn($course);
        $progress = $isEnrolled ? auth()->user()->getCourseProgress($course) : 0;

        return view('courses.show', compact('course', 'isEnrolled', 'progress'));
    }
}












