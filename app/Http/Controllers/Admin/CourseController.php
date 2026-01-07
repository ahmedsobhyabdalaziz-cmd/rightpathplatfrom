<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(Request $request): View
    {
        $query = Course::withCount(['modules', 'enrollments']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->has('status')) {
            if ($request->status === 'published') {
                $query->published();
            } elseif ($request->status === 'draft') {
                $query->draft();
            }
        }

        $courses = $query->latest()->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create(): View
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created course.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'what_you_learn' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        // Convert what_you_learn and requirements to arrays
        if (!empty($validated['what_you_learn'])) {
            $validated['what_you_learn'] = array_filter(
                array_map('trim', explode("\n", $validated['what_you_learn']))
            );
        }

        if (!empty($validated['requirements'])) {
            $validated['requirements'] = array_filter(
                array_map('trim', explode("\n", $validated['requirements']))
            );
        }

        Course::create($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course): View
    {
        $course->load(['modules.lessons', 'enrollments']);

        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the course.
     */
    public function edit(Course $course): View
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'what_you_learn' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        // Convert what_you_learn and requirements to arrays
        if (!empty($validated['what_you_learn'])) {
            $validated['what_you_learn'] = array_filter(
                array_map('trim', explode("\n", $validated['what_you_learn']))
            );
        } else {
            $validated['what_you_learn'] = null;
        }

        if (!empty($validated['requirements'])) {
            $validated['requirements'] = array_filter(
                array_map('trim', explode("\n", $validated['requirements']))
            );
        } else {
            $validated['requirements'] = null;
        }

        $course->update($validated);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course): RedirectResponse
    {
        // Delete thumbnail
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}










