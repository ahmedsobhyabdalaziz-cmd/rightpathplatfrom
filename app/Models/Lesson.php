<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_id',
        'title',
        'description',
        'content',
        'video_url',
        'video_type',
        'video_path',
        'attachments',
        'duration_minutes',
        'order',
        'is_free_preview',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'order' => 'integer',
            'duration_minutes' => 'integer',
            'is_free_preview' => 'boolean',
        ];
    }

    /**
     * Get the module that owns the lesson.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the course through module.
     */
    public function course()
    {
        return $this->module->course;
    }

    /**
     * Get the progress records for this lesson.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Get formatted duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        $minutes = $this->duration_minutes;
        
        if ($minutes >= 60) {
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            return $mins > 0 ? "{$hours}h {$mins}m" : "{$hours}h";
        }
        
        return "{$minutes}m";
    }

    /**
     * Get embed URL for video.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->video_type === 'upload' && $this->video_path) {
            return Storage::disk('public')->url($this->video_path);
        }

        if (!$this->video_url) {
            return null;
        }

        return match ($this->video_type) {
            'youtube' => $this->getYoutubeEmbedUrl(),
            'vimeo' => $this->getVimeoEmbedUrl(),
            'custom' => $this->video_url,
            default => null,
        };
    }

    /**
     * Get the direct video URL for uploaded videos.
     */
    public function getVideoFileUrl(): ?string
    {
        if ($this->video_type === 'upload' && $this->video_path) {
            return Storage::disk('public')->url($this->video_path);
        }
        return null;
    }

    /**
     * Get YouTube embed URL.
     */
    protected function getYoutubeEmbedUrl(): string
    {
        $videoId = $this->extractYoutubeId($this->video_url);
        return "https://www.youtube.com/embed/{$videoId}";
    }

    /**
     * Extract YouTube video ID from URL.
     */
    protected function extractYoutubeId(string $url): string
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? $url;
    }

    /**
     * Get Vimeo embed URL.
     */
    protected function getVimeoEmbedUrl(): string
    {
        $videoId = $this->extractVimeoId($this->video_url);
        return "https://player.vimeo.com/video/{$videoId}";
    }

    /**
     * Extract Vimeo video ID from URL.
     */
    protected function extractVimeoId(string $url): string
    {
        $pattern = '/vimeo\.com\/(?:video\/)?(\d+)/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? $url;
    }

    /**
     * Check if lesson has video.
     */
    public function hasVideo(): bool
    {
        if ($this->video_type === 'upload') {
            return !empty($this->video_path);
        }
        return !empty($this->video_url) && $this->video_type !== 'none';
    }

    /**
     * Check if lesson has attachments.
     */
    public function hasAttachments(): bool
    {
        return !empty($this->attachments) && count($this->attachments) > 0;
    }

    /**
     * Check if lesson is available for a user.
     */
    public function isAvailableFor(User $user): bool
    {
        // Free preview is always available
        if ($this->is_free_preview) {
            return true;
        }

        // Check if module is available (drip content)
        return $this->module->isAvailableFor($user);
    }

    /**
     * Get the next lesson in the course.
     */
    public function getNextLesson(): ?Lesson
    {
        // Try next lesson in same module
        $nextInModule = Lesson::where('module_id', $this->module_id)
            ->where('order', '>', $this->order)
            ->orderBy('order')
            ->first();

        if ($nextInModule) {
            return $nextInModule;
        }

        // Try first lesson of next module
        $nextModule = Module::where('course_id', $this->module->course_id)
            ->where('order', '>', $this->module->order)
            ->orderBy('order')
            ->first();

        return $nextModule?->lessons()->orderBy('order')->first();
    }

    /**
     * Get the previous lesson in the course.
     */
    public function getPreviousLesson(): ?Lesson
    {
        // Try previous lesson in same module
        $prevInModule = Lesson::where('module_id', $this->module_id)
            ->where('order', '<', $this->order)
            ->orderByDesc('order')
            ->first();

        if ($prevInModule) {
            return $prevInModule;
        }

        // Try last lesson of previous module
        $prevModule = Module::where('course_id', $this->module->course_id)
            ->where('order', '<', $this->module->order)
            ->orderByDesc('order')
            ->first();

        return $prevModule?->lessons()->orderByDesc('order')->first();
    }

    /**
     * Scope to order by position.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Scope for free preview lessons.
     */
    public function scopeFreePreview($query)
    {
        return $query->where('is_free_preview', true);
    }
}











