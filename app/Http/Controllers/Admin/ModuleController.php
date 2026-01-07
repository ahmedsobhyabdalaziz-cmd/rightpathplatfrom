<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    /**
     * Display a listing of modules for a course.
     */
    public function index(Course $course): View
    {
        $modules = $course->modules()->withCount('lessons')->ordered()->get();

        return view('admin.modules.index', compact('course', 'modules'));
    }

    /**
     * Show the form for creating a new module.
     */
    public function create(Course $course): View
    {
        return view('admin.modules.create', compact('course'));
    }

    /**
     * Store a newly created module.
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_type' => 'required|in:none,upload,youtube,vimeo,custom',
            'video_url' => 'nullable|url',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg,mov|max:512000', // 500MB max
            'video_duration_minutes' => 'nullable|integer|min:0',
            'drip_days' => 'nullable|integer|min:0',
        ]);

        // Set order to be last
        $validated['order'] = $course->modules()->max('order') + 1;
        $validated['drip_days'] = $validated['drip_days'] ?? 0;
        $validated['video_duration_minutes'] = $validated['video_duration_minutes'] ?? 0;

        // Handle video upload (store in private storage for protection)
        if ($request->hasFile('video_file') && $validated['video_type'] === 'upload') {
            $storageDisk = config('video.storage_disk', 'local');
            $validated['video_path'] = $request->file('video_file')->store('videos/modules', $storageDisk);
        }

        // Don't store file in database
        unset($validated['video_file']);

        $course->modules()->create($validated);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Module created successfully.');
    }

    /**
     * Display the specified module.
     */
    public function show(Module $module): View
    {
        $module->load(['course', 'lessons']);

        return view('admin.modules.show', compact('module'));
    }

    /**
     * Show the form for editing the module.
     */
    public function edit(Module $module): View
    {
        $module->load('course');

        return view('admin.modules.edit', compact('module'));
    }

    /**
     * Update the specified module.
     */
    public function update(Request $request, Module $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_type' => 'required|in:none,upload,youtube,vimeo,custom',
            'video_url' => 'nullable|url',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg,mov|max:512000', // 500MB max
            'video_duration_minutes' => 'nullable|integer|min:0',
            'remove_video' => 'nullable|boolean',
            'drip_days' => 'nullable|integer|min:0',
        ]);

        $validated['drip_days'] = $validated['drip_days'] ?? 0;
        $validated['video_duration_minutes'] = $validated['video_duration_minutes'] ?? 0;
        $storageDisk = config('video.storage_disk', 'local');

        // Handle video removal
        if ($request->boolean('remove_video') && $module->video_path) {
            Storage::disk($storageDisk)->delete($module->video_path);
            $validated['video_path'] = null;
        }

        // Handle new video upload (store in private storage for protection)
        if ($request->hasFile('video_file') && $validated['video_type'] === 'upload') {
            // Delete old video if exists
            if ($module->video_path) {
                Storage::disk($storageDisk)->delete($module->video_path);
            }
            $validated['video_path'] = $request->file('video_file')->store('videos/modules', $storageDisk);
        }

        // Clear video URL if type changed to upload or none
        if ($validated['video_type'] === 'upload' || $validated['video_type'] === 'none') {
            $validated['video_url'] = null;
        }

        // Clear video path if type changed to external
        if (in_array($validated['video_type'], ['youtube', 'vimeo', 'custom'])) {
            if ($module->video_path) {
                Storage::disk($storageDisk)->delete($module->video_path);
            }
            $validated['video_path'] = null;
        }

        // Don't store file in database
        unset($validated['video_file'], $validated['remove_video']);

        $module->update($validated);

        return redirect()
            ->route('admin.courses.show', $module->course)
            ->with('success', 'Module updated successfully.');
    }

    /**
     * Remove the specified module.
     */
    public function destroy(Module $module): RedirectResponse
    {
        $course = $module->course;
        $storageDisk = config('video.storage_disk', 'local');

        // Delete video file if exists from private storage
        if ($module->video_path) {
            Storage::disk($storageDisk)->delete($module->video_path);
        }

        $module->delete();

        // Reorder remaining modules
        $course->modules()->ordered()->get()->each(function ($m, $index) {
            $m->update(['order' => $index + 1]);
        });

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Module deleted successfully.');
    }

    /**
     * Reorder modules.
     */
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'modules' => 'required|array',
            'modules.*.id' => 'required|exists:modules,id',
            'modules.*.order' => 'required|integer|min:1',
        ]);

        foreach ($validated['modules'] as $moduleData) {
            Module::where('id', $moduleData['id'])->update(['order' => $moduleData['order']]);
        }

        return response()->json(['success' => true]);
    }
}









