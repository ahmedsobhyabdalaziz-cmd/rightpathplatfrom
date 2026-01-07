<x-app-layout>
    <x-slot name="title">Verify Certificate - RightPath LMS</x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
                @if($certificate)
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Certificate Verified</h1>
                    <p class="text-slate-600 mb-8">This certificate is authentic and valid.</p>

                    <div class="bg-slate-50 rounded-xl p-6 text-left">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm text-slate-500">Certificate Number</dt>
                                <dd class="font-medium text-slate-900">{{ $certificate->certificate_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-slate-500">Recipient</dt>
                                <dd class="font-medium text-slate-900">{{ $certificate->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-slate-500">Course</dt>
                                <dd class="font-medium text-slate-900">{{ $certificate->course->title }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-slate-500">Date Issued</dt>
                                <dd class="font-medium text-slate-900">{{ $certificate->issued_at->format('F j, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                @else
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Certificate Not Found</h1>
                    <p class="text-slate-600 mb-6">The certificate number <strong>{{ $certificate_number }}</strong> could not be verified.</p>
                    <p class="text-sm text-slate-500">Please check the certificate number and try again.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>











