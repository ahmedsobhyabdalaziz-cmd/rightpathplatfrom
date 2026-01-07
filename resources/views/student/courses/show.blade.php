<x-app-layout>
    <x-slot name="title">{{ $course->title }} - RightPath LMS</x-slot>

    <div class="bg-slate-100 min-h-screen">
        <!-- Course Header -->
        <div class="bg-white border-b border-slate-200 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <a href="{{ route('student.dashboard') }}" class="text-sm text-slate-500 hover:text-emerald-600 mb-2 inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                        <h1 class="text-2xl font-bold text-slate-900">{{ $course->title }}</h1>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm text-slate-500">Your Progress</p>
                            <p class="text-xl font-bold text-emerald-600">{{ $progress }}%</p>
                        </div>
                        <div class="w-32 bg-slate-200 rounded-full h-3">
                            <div class="bg-emerald-500 h-3 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Course Content -->
                <div class="lg:col-span-2">
                    @if($nextLesson)
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl p-6 mb-6 text-white">
                        <p class="text-emerald-100 text-sm mb-1">Up Next</p>
                        <h3 class="text-xl font-semibold mb-3">{{ $nextLesson->title }}</h3>
                        <a href="{{ route('student.lessons.show', ['course' => $course, 'lesson' => $nextLesson]) }}" 
                           class="inline-flex items-center px-5 py-2.5 bg-white text-emerald-600 font-medium rounded-lg hover:bg-emerald-50 transition">
                            Continue Learning
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                    @elseif($progress === 100)
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl p-6 mb-6 text-white">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="text-xl font-semibold">Congratulations!</h3>
                                <p class="text-amber-100">You've completed this course!</p>
                            </div>
                        </div>
                        <a href="{{ route('student.certificates.index') }}" class="inline-flex items-center mt-4 px-5 py-2.5 bg-white text-amber-600 font-medium rounded-lg hover:bg-amber-50 transition">
                            View Certificate
                        </a>
                    </div>
                    @endif

                    <!-- Curriculum -->
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200">
                            <h2 class="text-lg font-semibold text-slate-900">Course Content</h2>
                            <p class="text-sm text-slate-500">{{ $course->total_modules }} modules &middot; {{ $course->total_lessons }} lessons &middot; {{ $course->formatted_duration }}</p>
                        </div>
                        
                        <div class="divide-y divide-slate-200">
                            @foreach($modules as $module)
                            <div x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition text-left">
                                    <div class="flex items-center gap-3">
                                        @if($module->is_available)
                                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                                <span class="text-sm font-semibold text-emerald-600">{{ $loop->iteration }}</span>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-semibold text-slate-900">{{ $module->title }}</h3>
                                            <p class="text-sm text-slate-500">
                                                {{ $module->lessons->count() }} lessons
                                                @if(!$module->is_available && $module->unlock_date)
                                                    &middot; <span class="text-amber-600">Unlocks {{ $module->unlock_date->diffForHumans() }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" x-collapse class="bg-slate-50 border-t border-slate-200">
                                    <ul class="divide-y divide-slate-200">
                                        @foreach($module->lessons as $lesson)
                                        <li>
                                            @if($module->is_available)
                                            <a href="{{ route('student.lessons.show', ['course' => $course, 'lesson' => $lesson]) }}" 
                                               class="flex items-center justify-between px-6 py-3 hover:bg-slate-100 transition">
                                                <div class="flex items-center gap-3">
                                                    @if($lesson->is_completed)
                                                        <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    @elseif($lesson->hasVideo())
                                                        <div class="w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center">
                                                            <svg class="w-3 h-3 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                                                    @endif
                                                    <span class="text-slate-700 {{ $lesson->is_completed ? 'line-through text-slate-400' : '' }}">{{ $lesson->title }}</span>
                                                </div>
                                                <span class="text-sm text-slate-500">{{ $lesson->formatted_duration }}</span>
                                            </a>
                                            @else
                                            <div class="flex items-center justify-between px-6 py-3 text-slate-400">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                    <span>{{ $lesson->title }}</span>
                                                </div>
                                                <span class="text-sm">{{ $lesson->formatted_duration }}</span>
                                            </div>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="font-semibold text-slate-900 mb-4">Course Info</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Enrolled</dt>
                                <dd class="font-medium text-slate-900">{{ $enrollment->enrolled_at->format('M j, Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Duration</dt>
                                <dd class="font-medium text-slate-900">{{ $course->formatted_duration }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Modules</dt>
                                <dd class="font-medium text-slate-900">{{ $course->total_modules }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Lessons</dt>
                                <dd class="font-medium text-slate-900">{{ $course->total_lessons }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Difficulty</dt>
                                <dd class="font-medium text-slate-900 capitalize">{{ $course->difficulty }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if($course->what_you_learn && count($course->what_you_learn) > 0)
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="font-semibold text-slate-900 mb-4">What You'll Learn</h3>
                        <ul class="space-y-2">
                            @foreach($course->what_you_learn as $item)
                            <li class="flex items-start text-sm">
                                <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-slate-600">{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>










