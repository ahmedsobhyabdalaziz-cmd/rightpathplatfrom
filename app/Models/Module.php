<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_path',
        'video_type',
        'video_url',
        'video_duration_minutes',
        'order',
        'drip_days',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'drip_days' => 'integer',
            'video_duration_minutes' => 'integer',
        ];
    }

    /**
     * Check if module has a video.
     */
    public function hasVideo(): bool
    {
        return $this->video_type !== 'none' && ($this->video_path || $this->video_url);
    }

    /**
     * Get video URL for display.
     */
    public function getVideoDisplayUrlAttribute(): ?string
    {
        if ($this->video_type === 'upload' && $this->video_path) {
            return asset('storage/' . $this->video_path);
        }

        if ($this->video_url) {
            return $this->video_url;
        }

        return null;
    }

    /**
     * Get embed URL for YouTube/Vimeo videos.
     */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        if ($this->video_type === 'youtube') {
            // Extract video ID from various YouTube URL formats
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->video_url, $matches);
            if (isset($matches[1])) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        }

        if ($this->video_type === 'vimeo') {
            // Extract video ID from Vimeo URL
            preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $this->video_url, $matches);
            if (isset($matches[1])) {
                return 'https://player.vimeo.com/video/' . $matches[1];
            }
        }

        return $this->video_url;
    }

    /**
     * Get the course that owns the module.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lessons for the module.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * Get total duration in minutes.
     */
    public function getTotalDurationAttribute(): int
    {
        return $this->lessons()->sum('duration_minutes');
    }

    /**
     * Get total number of lessons.
     */
    public function getTotalLessonsAttribute(): int
    {
        return $this->lessons()->count();
    }

    /**
     * Get formatted duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        $minutes = $this->total_duration;
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0 && $mins > 0) {
            return "{$hours}h {$mins}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$mins}m";
        }
    }

    /**
     * Check if module is available for a user based on drip content.
     */
    public function isAvailableFor(User $user): bool
    {
        // Admins always have access
        if ($user->isAdmin()) {
            return true;
        }

        // If no drip days, always available
        if ($this->drip_days === 0) {
            return true;
        }

        $enrollment = $user->getEnrollment($this->course);
        
        if (!$enrollment) {
            return false;
        }

        $unlockDate = $enrollment->enrolled_at->addDays($this->drip_days);
        
        return now()->gte($unlockDate);
    }

    /**
     * Get unlock date for a user.
     */
    public function getUnlockDateFor(User $user): ?\Carbon\Carbon
    {
        if ($this->drip_days === 0) {
            return null;
        }

        $enrollment = $user->getEnrollment($this->course);
        
        if (!$enrollment) {
            return null;
        }

        return $enrollment->enrolled_at->addDays($this->drip_days);
    }

    /**
     * Scope to order by position.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}









