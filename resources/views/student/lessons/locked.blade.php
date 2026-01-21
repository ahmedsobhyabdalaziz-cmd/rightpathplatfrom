<x-app-layout>
    <x-slot name="title">Lesson Locked - RightPath LMS</x-slot>

    <div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
        <div class="text-center">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Lesson Locked</h1>
            <p class="text-slate-600 mb-6 max-w-md">
                This lesson will become available 
                @if($unlockDate)
                    <span class="font-medium text-emerald-600">{{ $unlockDate->format('F j, Y') }}</span>
                    ({{ $unlockDate->diffForHumans() }})
                @endif
            </p>
            <a href="{{ route('student.courses.show', $course) }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Course
            </a>
        </div>
    </div>
</x-app-layout>












