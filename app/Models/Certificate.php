<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_number',
        'issued_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificate) {
            if (empty($certificate->certificate_number)) {
                $certificate->certificate_number = self::generateCertificateNumber();
            }
            if (empty($certificate->issued_at)) {
                $certificate->issued_at = now();
            }
        });
    }

    /**
     * Generate a unique certificate number.
     */
    public static function generateCertificateNumber(): string
    {
        do {
            $number = 'CERT-' . strtoupper(Str::random(8)) . '-' . date('Y');
        } while (self::where('certificate_number', $number)->exists());

        return $number;
    }

    /**
     * Get the user that owns the certificate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course that owns the certificate.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the verification URL.
     */
    public function getVerificationUrlAttribute(): string
    {
        return route('certificates.verify', $this->certificate_number);
    }

    /**
     * Get formatted issue date.
     */
    public function getFormattedIssueDateAttribute(): string
    {
        return $this->issued_at->format('F j, Y');
    }
}











