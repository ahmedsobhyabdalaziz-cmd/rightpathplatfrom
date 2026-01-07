<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * Display a listing of lessons for a module.
     */
    public function index(Module $module): View
    {
        $lessons = $module->lessons()->ordered()->get();

        return view('admin.lessons.index', compact('module', 'lessons'));
    }

    /**
     * Show the form for creating a new lesson.
     */
    public function create(Module $module): View
    {
        return view('admin.lessons.create', compact('module'));
    }

    /**
     * Store a newly created lesson.
     */
    public function store(Request $request, Module $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video_type' => 'required|in:youtube,vimeo,custom,none',
            'duration_minutes' => 'nullable|integer|min:0',
            'is_free_preview' => 'boolean',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        // Set order to be last
        $validated['order'] = $module->lessons()->max('order') + 1;
        $validated['is_free_preview'] = $request->boolean('is_free_preview');
        $validated['duration_minutes'] = $validated['duration_minutes'] ?? 0;

        // Handle attachments upload
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('lessons/attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        $module->lessons()->create($validated);

        return redirect()
            ->route('admin.courses.show', $module->course)
            ->with('success', 'Lesson created successfully.');
    }

    /**
     * Display the specified lesson.
     */
    public function show(Lesson $lesson): View
    {
        $lesson->load('module.course');

        return view('admin.lessons.show', compact('lesson'));
    }

    /**
     * Show the form for editing the lesson.
     */
    public function edit(Lesson $lesson): View
    {
        $lesson->load('module.course');

        return view('admin.lessons.edit', compact('lesson'));
    }

    /**
     * Update the specified lesson.
     */
    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video_type' => 'required|in:youtube,vimeo,custom,none',
            'duration_minutes' => 'nullable|integer|min:0',
            'is_free_preview' => 'boolean',
            'attachments.*' => 'nullable|file|max:10240',
            'remove_attachments' => 'nullable|array',
        ]);

        $validated['is_free_preview'] = $request->boolean('is_free_preview');
        $validated['duration_minutes'] = $validated['duration_minutes'] ?? 0;

        // Handle attachment removal
        $currentAttachments = $lesson->attachments ?? [];
        if ($request->has('remove_attachments')) {
            foreach ($request->remove_attachments as $index) {
                if (isset($currentAttachments[$index])) {
                    Storage::disk('public')->delete($currentAttachments[$index]['path']);
                    unset($currentAttachments[$index]);
                }
            }
            $currentAttachments = array_values($currentAttachments);
        }

        // Handle new attachments upload
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('lessons/attachments', 'public');
                $currentAttachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }
        }

        $validated['attachments'] = $currentAttachments;
        unset($validated['remove_attachments']);

        $lesson->update($validated);

        return redirect()
            ->route('admin.courses.show', $lesson->module->course)
            ->with('success', 'Lesson updated successfully.');
    }

    /**
     * Remove the specified lesson.
     */
    public function destroy(Lesson $lesson): RedirectResponse
    {
        $module = $lesson->module;
        $course = $module->course;

        // Delete attachments
        if ($lesson->attachments) {
            foreach ($lesson->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $lesson->delete();

        // Reorder remaining lessons
        $module->lessons()->ordered()->get()->each(function ($l, $index) {
            $l->update(['order' => $index + 1]);
        });

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Lesson deleted successfully.');
    }

    /**
     * Reorder lessons.
     */
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|exists:lessons,id',
            'lessons.*.order' => 'required|integer|min:1',
        ]);

        foreach ($validated['lessons'] as $lessonData) {
            Lesson::where('id', $lessonData['id'])->update(['order' => $lessonData['order']]);
        }

        return response()->json(['success' => true]);
    }
}










