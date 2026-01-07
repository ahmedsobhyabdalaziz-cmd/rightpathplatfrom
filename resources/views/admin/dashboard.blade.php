<x-admin-layout>
    <x-slot name="header">Dashboard</x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-slate-500">Total Courses</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['total_courses'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-slate-500">Published</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['published_courses'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-slate-500">Students</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['total_students'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-slate-500">Enrollments</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['total_enrollments'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-slate-500">Certificates</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['certificates_issued'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Recent Enrollments -->
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Recent Enrollments</h2>
            </div>
            <div class="divide-y divide-slate-200">
                @forelse($recentEnrollments as $enrollment)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-slate-600">{{ substr($enrollment->user->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-slate-900">{{ $enrollment->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $enrollment->course->title }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500">{{ $enrollment->enrolled_at->diffForHumans() }}</span>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-slate-500">
                    No enrollments yet.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Popular Courses -->
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Popular Courses</h2>
            </div>
            <div class="divide-y divide-slate-200">
                @forelse($popularCourses as $course)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <a href="{{ route('admin.courses.show', $course) }}" class="text-sm font-medium text-slate-900 hover:text-emerald-600">{{ $course->title }}</a>
                            <p class="text-xs text-slate-500">{{ $course->enrollments_count }} enrollments</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $course->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $course->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-slate-500">
                    No courses yet.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.courses.create') }}" class="bg-white rounded-xl border border-slate-200 p-6 hover:border-emerald-300 hover:shadow-md transition group">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-200 transition">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-slate-900">Create New Course</p>
                        <p class="text-sm text-slate-500">Add a new course to your catalog</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.courses.index') }}" class="bg-white rounded-xl border border-slate-200 p-6 hover:border-emerald-300 hover:shadow-md transition group">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center group-hover:bg-teal-200 transition">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-slate-900">Manage Courses</p>
                        <p class="text-sm text-slate-500">View and edit existing courses</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.students.index') }}" class="bg-white rounded-xl border border-slate-200 p-6 hover:border-emerald-300 hover:shadow-md transition group">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-slate-900">View Students</p>
                        <p class="text-sm text-slate-500">Manage student enrollments</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-admin-layout>










