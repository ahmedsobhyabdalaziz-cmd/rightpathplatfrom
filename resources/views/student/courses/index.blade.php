<x-app-layout>
    <x-slot name="title">My Courses - RightPath LMS</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-8">My Courses</h1>

            <!-- Enrolled Courses -->
            @if($enrolledCourses->isNotEmpty())
            <div class="mb-12">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Enrolled Courses</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrolledCourses as $enrollment)
                    <a href="{{ route('student.courses.show', $enrollment->course) }}" class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition group">
                        @if($enrollment->course->thumbnail)
                            <div class="aspect-video bg-slate-100 overflow-hidden">
                                <img src="{{ Storage::url($enrollment->course->thumbnail) }}" alt="{{ $enrollment->course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                        @else
                            <div class="aspect-video bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-900 group-hover:text-emerald-600 transition">{{ $enrollment->course->title }}</h3>
                            <div class="mt-3">
                                <div class="flex items-center justify-between text-sm text-slate-500 mb-2">
                                    <span>Progress</span>
                                    <span>{{ $enrollment->progress_percent }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $enrollment->progress_percent }}%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Available Courses -->
            <div>
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Available Courses</h2>
                @if($availableCourses->isEmpty())
                <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                    <p class="text-slate-600">You've enrolled in all available courses!</p>
                </div>
                @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($availableCourses as $course)
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition group">
                        @if($course->thumbnail)
                            <div class="aspect-video bg-slate-100 overflow-hidden">
                                <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                        @else
                            <div class="aspect-video bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full 
                                    {{ $course->difficulty === 'beginner' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $course->difficulty === 'intermediate' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $course->difficulty === 'advanced' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($course->difficulty) }}
                                </span>
                                <span class="text-xs text-slate-500">{{ $course->modules_count }} modules</span>
                            </div>
                            <h3 class="font-semibold text-slate-900 mb-2">{{ $course->title }}</h3>
                            <p class="text-sm text-slate-600 line-clamp-2 mb-4">{{ $course->short_description }}</p>
                            <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                                    Enroll Now
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $availableCourses->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>










