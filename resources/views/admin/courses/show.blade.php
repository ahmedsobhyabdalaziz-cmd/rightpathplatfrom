<x-admin-layout>
    <x-slot name="header">{{ $course->title }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <span class="px-3 py-1 text-sm font-medium rounded-full {{ $course->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                {{ $course->is_published ? 'Published' : 'Draft' }}
            </span>
            <span class="text-sm text-slate-500">{{ $course->enrollments->count() }} students enrolled</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.courses.edit', $course) }}" class="inline-flex items-center px-4 py-2 border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium rounded-lg transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Course
            </a>
            <a href="{{ route('admin.courses.modules.create', $course) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Module
            </a>
        </div>
    </div>

    <!-- Course Info -->
    <div class="grid lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Course Details</h2>
            <div class="space-y-4">
                @if($course->short_description)
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Short Description</p>
                    <p class="text-slate-700">{{ $course->short_description }}</p>
                </div>
                @endif
                @if($course->description)
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Full Description</p>
                    <p class="text-slate-700 whitespace-pre-line">{{ $course->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-sm font-semibold text-slate-900 mb-4">Statistics</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Difficulty</dt>
                        <dd class="font-medium text-slate-900 capitalize">{{ $course->difficulty }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Modules</dt>
                        <dd class="font-medium text-slate-900">{{ $course->modules->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Lessons</dt>
                        <dd class="font-medium text-slate-900">{{ $course->total_lessons }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Duration</dt>
                        <dd class="font-medium text-slate-900">{{ $course->formatted_duration }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Modules & Lessons -->
    <div class="bg-white rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Curriculum</h2>
        </div>
        
        @if($course->modules->isEmpty())
        <div class="px-6 py-12 text-center text-slate-500">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <p>No modules yet.</p>
            <a href="{{ route('admin.courses.modules.create', $course) }}" class="inline-flex items-center mt-4 text-emerald-600 hover:text-emerald-700 font-medium">
                Add your first module
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
        @else
        <div class="divide-y divide-slate-200">
            @foreach($course->modules as $module)
            <div class="p-6" x-data="{ open: true }">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <button @click="open = !open" class="mr-3 text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div>
                            <h3 class="font-semibold text-slate-900">{{ $module->order }}. {{ $module->title }}</h3>
                            <p class="text-sm text-slate-500">
                                {{ $module->lessons->count() }} lessons
                                @if($module->drip_days > 0)
                                    &middot; Unlocks after {{ $module->drip_days }} days
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.modules.lessons.create', $module) }}" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Add Lesson">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.modules.edit', $module) }}" class="p-2 text-slate-400 hover:text-emerald-600 rounded-lg transition" title="Edit Module">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.modules.destroy', $module) }}" onsubmit="return confirm('Delete this module and all its lessons?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 rounded-lg transition" title="Delete Module">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div x-show="open" x-collapse>
                    @if($module->lessons->isEmpty())
                    <p class="text-sm text-slate-500 py-4 pl-8">No lessons yet. <a href="{{ route('admin.modules.lessons.create', $module) }}" class="text-emerald-600 hover:text-emerald-700">Add one</a></p>
                    @else
                    <ul class="space-y-2 pl-8">
                        @foreach($module->lessons as $lesson)
                        <li class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-slate-50 group">
                            <div class="flex items-center">
                                @if($lesson->hasVideo())
                                    <svg class="w-4 h-4 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @endif
                                <span class="text-sm text-slate-700">{{ $lesson->order }}. {{ $lesson->title }}</span>
                                @if($lesson->is_free_preview)
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700 rounded">Preview</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                                <span class="text-xs text-slate-500 mr-2">{{ $lesson->formatted_duration }}</span>
                                <a href="{{ route('admin.lessons.edit', $lesson) }}" class="p-1 text-slate-400 hover:text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" onsubmit="return confirm('Delete this lesson?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-400 hover:text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</x-admin-layout>









