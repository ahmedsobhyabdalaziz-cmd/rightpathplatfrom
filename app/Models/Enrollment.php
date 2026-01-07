<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
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
        'enrolled_at',
        'completed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the enrollment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course that owns the enrollment.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Check if course is completed.
     */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Mark enrollment as completed.
     */
    public function markCompleted(): void
    {
        $this->update(['completed_at' => now()]);
    }

    /**
     * Get progress percentage.
     */
    public function getProgressAttribute(): int
    {
        return $this->user->getCourseProgress($this->course);
    }

    /**
     * Scope for completed enrollments.
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    /**
     * Scope for incomplete enrollments.
     */
    public function scopeIncomplete($query)
    {
        return $query->whereNull('completed_at');
    }
}











