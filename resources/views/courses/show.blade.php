<x-app-layout>
    <x-slot name="title">{{ $course->title }} - RightPath LMS</x-slot>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-slate-900 to-slate-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-12">
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            {{ $course->difficulty === 'beginner' ? 'bg-green-500/20 text-green-300' : '' }}
                            {{ $course->difficulty === 'intermediate' ? 'bg-yellow-500/20 text-yellow-300' : '' }}
                            {{ $course->difficulty === 'advanced' ? 'bg-red-500/20 text-red-300' : '' }}">
                            {{ ucfirst($course->difficulty) }}
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold">{{ $course->title }}</h1>
                    <p class="mt-4 text-lg text-slate-300">{{ $course->short_description }}</p>
                    
                    <div class="mt-6 flex flex-wrap items-center gap-6 text-slate-300">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $course->formatted_duration }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            {{ $course->total_modules }} modules
                        </span>
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            {{ $course->total_lessons }} lessons
                        </span>
                    </div>

                    @if($isEnrolled)
                        <div class="mt-8">
                            <div class="flex items-center gap-4 mb-3">
                                <span class="text-sm text-slate-300">Your Progress</span>
                                <span class="text-sm font-medium text-emerald-400">{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-slate-700 rounded-full h-2">
                                <div class="bg-emerald-500 h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                            </div>
                            <a href="{{ route('student.courses.show', $course) }}" class="mt-6 inline-flex items-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition">
                                Continue Learning
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    @else
                        <div class="mt-8">
                            @auth
                                <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition">
                                        Enroll Now - Free
                                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition">
                                    Sign Up to Enroll
                                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>

                <div class="hidden lg:block">
                    @if($course->thumbnail)
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="rounded-2xl shadow-2xl">
                    @else
                        <div class="aspect-video bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-20 h-20 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Course Content -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-12">
                    <!-- About -->
                    @if($course->description)
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-4">About This Course</h2>
                        <div class="prose prose-slate max-w-none">
                            {!! nl2br(e($course->description)) !!}
                        </div>
                    </div>
                    @endif

                    <!-- What You'll Learn -->
                    @if($course->what_you_learn && count($course->what_you_learn) > 0)
                    <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">What You'll Learn</h2>
                        <ul class="grid md:grid-cols-2 gap-3">
                            @foreach($course->what_you_learn as $item)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-slate-700">{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Curriculum -->
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Course Curriculum</h2>
                        <div class="space-y-4">
                            @foreach($course->modules as $module)
                            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition">
                                    <div class="flex items-center">
                                        <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 font-semibold text-sm flex items-center justify-center mr-3">
                                            {{ $loop->iteration }}
                                        </span>
                                        <div class="text-left">
                                            <h3 class="font-semibold text-slate-900">{{ $module->title }}</h3>
                                            <p class="text-sm text-slate-500">{{ $module->lessons->count() }} lessons &middot; {{ $module->formatted_duration }}</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse class="border-t border-slate-200">
                                    <ul class="divide-y divide-slate-100">
                                        @foreach($module->lessons as $lesson)
                                        <li class="px-6 py-3 flex items-center justify-between">
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
                                                <span class="text-slate-700">{{ $lesson->title }}</span>
                                                @if($lesson->is_free_preview)
                                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700 rounded">Preview</span>
                                                @endif
                                            </div>
                                            <span class="text-sm text-slate-500">{{ $lesson->formatted_duration }}</span>
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
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        <!-- Requirements -->
                        @if($course->requirements && count($course->requirements) > 0)
                        <div class="bg-white rounded-xl border border-slate-200 p-6">
                            <h3 class="font-semibold text-slate-900 mb-4">Requirements</h3>
                            <ul class="space-y-2">
                                @foreach($course->requirements as $req)
                                <li class="flex items-start text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-slate-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    {{ $req }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Course Includes -->
                        <div class="bg-white rounded-xl border border-slate-200 p-6">
                            <h3 class="font-semibold text-slate-900 mb-4">This Course Includes</h3>
                            <ul class="space-y-3">
                                <li class="flex items-center text-sm text-slate-600">
                                    <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $course->total_lessons }} video lessons
                                </li>
                                <li class="flex items-center text-sm text-slate-600">
                                    <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $course->formatted_duration }} total duration
                                </li>
                                <li class="flex items-center text-sm text-slate-600">
                                    <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    Certificate of completion
                                </li>
                                <li class="flex items-center text-sm text-slate-600">
                                    <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                    </svg>
                                    Lifetime access
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>












