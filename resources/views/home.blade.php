<x-app-layout>
    <x-slot name="title">Welcome - RightPath LMS</x-slot>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-900 text-white overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-50"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight">
                    Learn Skills That
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">Transform</span>
                    Your Career
                </h1>
                <p class="mt-6 text-lg md:text-xl text-slate-300 leading-relaxed">
                    Access premium courses designed to help you master new skills, advance your career, and achieve your goals at your own pace.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('courses.public') }}" class="inline-flex items-center justify-center px-8 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition shadow-lg shadow-emerald-500/25">
                        Browse Courses
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition backdrop-blur">
                            Get Started Free
                        </a>
                    @endguest
                </div>
            </div>
        </div>

        <!-- Wave Divider -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="w-full h-16 text-slate-50" viewBox="0 0 1440 54" fill="currentColor" preserveAspectRatio="none">
                <path d="M0 22L60 16.7C120 11 240 1 360 0.7C480 1 600 11 720 16.7C840 22 960 22 1080 18.3C1200 15 1320 7 1380 3.7L1440 0V54H1380C1320 54 1200 54 1080 54C960 54 840 54 720 54C600 54 480 54 360 54C240 54 120 54 60 54H0V22Z"></path>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Why Learn With Us?</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Our platform is designed to provide you with the best learning experience.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Learn at Your Pace</h3>
                    <p class="text-slate-600">Access course materials anytime, anywhere. Progress through lessons at a speed that works for you.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Earn Certificates</h3>
                    <p class="text-slate-600">Complete courses and earn verifiable certificates to showcase your new skills to employers.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-cyan-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Track Progress</h3>
                    <p class="text-slate-600">Monitor your learning journey with detailed progress tracking and achievement milestones.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    @if($featuredCourses->isNotEmpty())
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Featured Courses</h2>
                    <p class="mt-2 text-lg text-slate-600">Start learning with our most popular courses</p>
                </div>
                <a href="{{ route('courses.public') }}" class="hidden md:inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium">
                    View All Courses
                    <svg class="ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredCourses as $course)
                <a href="{{ route('courses.show.public', $course) }}" class="group bg-slate-50 rounded-2xl overflow-hidden hover:shadow-xl transition duration-300">
                    @if($course->thumbnail)
                        <div class="aspect-video bg-slate-200 overflow-hidden">
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                    @else
                        <div class="aspect-video bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full 
                                {{ $course->difficulty === 'beginner' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $course->difficulty === 'intermediate' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $course->difficulty === 'advanced' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($course->difficulty) }}
                            </span>
                            <span class="text-sm text-slate-500">{{ $course->formatted_duration }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 group-hover:text-emerald-600 transition">{{ $course->title }}</h3>
                        <p class="mt-2 text-slate-600 text-sm line-clamp-2">{{ $course->short_description }}</p>
                        <div class="mt-4 flex items-center text-sm text-slate-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            {{ $course->enrollments_count }} enrolled
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-8 text-center md:hidden">
                <a href="{{ route('courses.public') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium">
                    View All Courses
                    <svg class="ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-emerald-600 to-teal-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white">Ready to Start Learning?</h2>
            <p class="mt-4 text-lg text-emerald-100">Join thousands of students who are already advancing their careers with our courses.</p>
            <div class="mt-8">
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white text-emerald-600 font-semibold rounded-xl hover:bg-emerald-50 transition shadow-lg">
                        Create Free Account
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-8 py-4 bg-white text-emerald-600 font-semibold rounded-xl hover:bg-emerald-50 transition shadow-lg">
                        Go to Dashboard
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                @endguest
            </div>
        </div>
    </section>
</x-app-layout>












