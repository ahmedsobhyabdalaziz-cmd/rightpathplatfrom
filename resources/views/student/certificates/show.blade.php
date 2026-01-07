<x-app-layout>
    <x-slot name="title">Certificate - {{ $certificate->course->title }}</x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('student.certificates.index') }}" class="text-slate-600 hover:text-emerald-600 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Certificates
                </a>
                <a href="{{ route('student.certificates.download', $certificate) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
            </div>

            <!-- Certificate Preview -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-lg">
                <div class="aspect-[1.414/1] bg-gradient-to-br from-slate-50 to-slate-100 p-8 md:p-12 flex flex-col items-center justify-center text-center">
                    <div class="border-4 border-emerald-500 rounded-lg p-8 md:p-12 w-full max-w-2xl bg-white">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>

                        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2">Certificate of Completion</h1>
                        <p class="text-slate-500 mb-8">This is to certify that</p>

                        <h2 class="text-3xl md:text-4xl font-bold text-emerald-600 mb-8">{{ $certificate->user->name }}</h2>

                        <p class="text-slate-500 mb-2">has successfully completed</p>
                        <h3 class="text-xl md:text-2xl font-semibold text-slate-900 mb-8">{{ $certificate->course->title }}</h3>

                        <div class="flex items-center justify-center gap-8 text-sm text-slate-500">
                            <div>
                                <p class="font-semibold text-slate-700">Date Issued</p>
                                <p>{{ $certificate->issued_at->format('F j, Y') }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-700">Certificate ID</p>
                                <p>{{ $certificate->certificate_number }}</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-slate-200">
                            <p class="text-xs text-slate-400">
                                Verify at: {{ route('certificates.verify', $certificate->certificate_number) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>










