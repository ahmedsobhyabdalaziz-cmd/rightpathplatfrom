<x-app-layout>
    <x-slot name="title">My Dashboard - RightPath LMS</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="mt-2 text-slate-600">Continue your learning journey</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">Enrolled Courses</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['total_courses'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">In Progress</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">Completed</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['completed_courses'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">Certificates</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['certificates'] }}</p>
                </div>
            </div>

            <!-- Continue Learning -->
            @if($inProgressCourses->isNotEmpty())
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Continue Learning</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($inProgressCourses->take(3) as $enrollment)
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition">
                        @if($enrollment->course->thumbnail)
                            <div class="aspect-video bg-slate-100 overflow-hidden">
                                <img src="{{ Storage::url($enrollment->course->thumbnail) }}" alt="{{ $enrollment->course->title }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="aspect-video bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-900 mb-2">{{ $enrollment->course->title }}</h3>
                            
                            <div class="flex items-center justify-between text-sm text-slate-500 mb-3">
                                <span>{{ $enrollment->progress_percent }}% complete</span>
                                <span>{{ $enrollment->course->total_lessons }} lessons</span>
                            </div>
                            
                            <div class="w-full bg-slate-100 rounded-full h-2 mb-4">
                                <div class="bg-emerald-500 h-2 rounded-full transition-all" style="width: {{ $enrollment->progress_percent }}%"></div>
                            </div>

                            @if($enrollment->next_lesson)
                                <a href="{{ route('student.lessons.show', ['course' => $enrollment->course, 'lesson' => $enrollment->next_lesson]) }}" 
                                   class="inline-flex items-center w-full justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                                    Continue
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('student.courses.show', $enrollment->course) }}" 
                                   class="inline-flex items-center w-full justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                                    View Course
                                </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- All Enrollments -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-slate-900">All My Courses</h2>
                    <a href="{{ route('student.courses.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm">
                        Browse More Courses
                    </a>
                </div>
                
                @if($enrollments->isEmpty())
                <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">No courses yet</h3>
                    <p class="text-slate-600 mb-4">Start your learning journey by enrolling in a course.</p>
                    <a href="{{ route('student.courses.index') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                        Browse Courses
                    </a>
                </div>
                @else
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Enrolled</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($enrollments as $enrollment)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <a href="{{ route('student.courses.show', $enrollment->course) }}" class="font-medium text-slate-900 hover:text-emerald-600">
                                        {{ $enrollment->course->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-24 bg-slate-100 rounded-full h-2 mr-3">
                                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ auth()->user()->getCourseProgress($enrollment->course) }}%"></div>
                                        </div>
                                        <span class="text-sm text-slate-600">{{ auth()->user()->getCourseProgress($enrollment->course) }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $enrollment->enrolled_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($enrollment->completed_at)
                                        <span class="px-2.5 py-1 text-xs font-medium bg-emerald-100 text-emerald-700 rounded-full">Completed</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">In Progress</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>










