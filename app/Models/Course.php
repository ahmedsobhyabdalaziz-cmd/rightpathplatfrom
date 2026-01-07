<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'thumbnail',
        'is_published',
        'duration_hours',
        'difficulty',
        'what_you_learn',
        'requirements',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'what_you_learn' => 'array',
            'requirements' => 'array',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the modules for the course.
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    /**
     * Get all lessons for the course through modules.
     */
    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, Module::class);
    }

    /**
     * Get the enrollments for the course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get enrolled students.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('enrolled_at', 'completed_at')
            ->withTimestamps();
    }

    /**
     * Get the certificates for the course.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get total duration in minutes.
     */
    public function getTotalDurationAttribute(): int
    {
        return $this->lessons()->sum('lessons.duration_minutes');
    }

    /**
     * Get total number of lessons.
     */
    public function getTotalLessonsAttribute(): int
    {
        return $this->lessons()->count('lessons.id');
    }

    /**
     * Get total number of modules.
     */
    public function getTotalModulesAttribute(): int
    {
        return $this->modules()->count();
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
     * Get the first lesson of the course.
     */
    public function getFirstLessonAttribute(): ?Lesson
    {
        $firstModule = $this->modules()->orderBy('order')->first();
        return $firstModule?->lessons()->orderBy('order')->first();
    }

    /**
     * Scope for published courses.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for unpublished courses.
     */
    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Scope for filtering by difficulty.
     */
    public function scopeDifficulty($query, string $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }
}









