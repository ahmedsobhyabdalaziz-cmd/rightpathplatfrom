<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateService
{
    /**
     * Generate a certificate for a completed course.
     */
    public function generateCertificate(User $user, Course $course): Certificate
    {
        // Check if certificate already exists
        $existing = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        return Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_number' => Certificate::generateCertificateNumber(),
            'issued_at' => now(),
        ]);
    }

    /**
     * Generate PDF for a certificate.
     */
    public function generatePdf(Certificate $certificate)
    {
        $certificate->load(['user', 'course']);

        $data = [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'course' => $certificate->course,
            'issued_date' => $certificate->issued_at->format('F j, Y'),
            'certificate_number' => $certificate->certificate_number,
            'verification_url' => route('certificates.verify', $certificate->certificate_number),
        ];

        $pdf = Pdf::loadView('certificates.pdf', $data);
        
        $pdf->setPaper('A4', 'landscape');

        return $pdf;
    }

    /**
     * Verify a certificate by its number.
     */
    public function verifyCertificate(string $certificateNumber): ?Certificate
    {
        return Certificate::where('certificate_number', $certificateNumber)
            ->with(['user', 'course'])
            ->first();
    }

    /**
     * Check if user has certificate for a course.
     */
    public function hasCertificate(User $user, Course $course): bool
    {
        return Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();
    }

    /**
     * Get certificate for a user and course.
     */
    public function getCertificate(User $user, Course $course): ?Certificate
    {
        return Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
    }
}











