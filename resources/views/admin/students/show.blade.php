<x-admin-layout>
    <x-slot name="header">Student: {{ $user->name }}</x-slot>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Student Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-medium text-slate-600">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <h2 class="text-xl font-semibold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-slate-500">{{ $user->email }}</p>
                </div>
                
                <dl class="mt-6 space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Joined</dt>
                        <dd class="font-medium text-slate-900">{{ $user->created_at->format('M j, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Enrollments</dt>
                        <dd class="font-medium text-slate-900">{{ $user->enrollments->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Certificates</dt>
                        <dd class="font-medium text-slate-900">{{ $user->certificates->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Enrollments -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Enrolled Courses</h3>
                </div>
                <div class="divide-y divide-slate-200">
                    @forelse($enrollments as $enrollment)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="{{ route('admin.courses.show', $enrollment->course) }}" class="font-medium text-slate-900 hover:text-emerald-600">
                                    {{ $enrollment->course->title }}
                                </a>
                                <p class="text-sm text-slate-500">Enrolled {{ $enrollment->enrolled_at->format('M j, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-slate-900">{{ $user->getCourseProgress($enrollment->course) }}%</p>
                                @if($enrollment->completed_at)
                                    <span class="text-xs text-emerald-600">Completed</span>
                                @else
                                    <span class="text-xs text-slate-500">In Progress</span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2 w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $user->getCourseProgress($enrollment->course) }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-12 text-center text-slate-500">
                        No enrollments yet.
                    </div>
                    @endforelse
                </div>
            </div>

            @if($user->certificates->isNotEmpty())
            <div class="mt-6 bg-white rounded-xl border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Certificates Earned</h3>
                </div>
                <div class="divide-y divide-slate-200">
                    @foreach($user->certificates as $certificate)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-slate-900">{{ $certificate->course->title }}</p>
                            <p class="text-sm text-slate-500">{{ $certificate->certificate_number }}</p>
                        </div>
                        <span class="text-sm text-slate-500">{{ $certificate->issued_at->format('M j, Y') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>











