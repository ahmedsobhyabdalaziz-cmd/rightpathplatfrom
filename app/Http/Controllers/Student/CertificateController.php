<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CertificateController extends Controller
{
    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Display user's certificates.
     */
    public function index(): View
    {
        $certificates = auth()->user()
            ->certificates()
            ->with('course')
            ->latest('issued_at')
            ->get();

        return view('student.certificates.index', compact('certificates'));
    }

    /**
     * Display a certificate.
     */
    public function show(Certificate $certificate): View
    {
        // Ensure user owns this certificate
        if ($certificate->user_id !== auth()->id()) {
            abort(403);
        }

        $certificate->load(['user', 'course']);

        return view('student.certificates.show', compact('certificate'));
    }

    /**
     * Download certificate as PDF.
     */
    public function download(Certificate $certificate): Response
    {
        // Ensure user owns this certificate
        if ($certificate->user_id !== auth()->id()) {
            abort(403);
        }

        $certificate->load(['user', 'course']);

        $pdf = $this->certificateService->generatePdf($certificate);

        $filename = 'certificate-' . $certificate->certificate_number . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Verify a certificate (public).
     */
    public function verify(string $certificate_number): View
    {
        $certificate = Certificate::where('certificate_number', $certificate_number)
            ->with(['user', 'course'])
            ->first();

        return view('certificates.verify', compact('certificate', 'certificate_number'));
    }
}










