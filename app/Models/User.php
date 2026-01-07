<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Get the user's enrollments.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the user's enrolled courses.
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('enrolled_at', 'completed_at')
            ->withTimestamps();
    }

    /**
     * Get the user's lesson progress.
     */
    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Get the user's certificates.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Check if user is enrolled in a course.
     */
    public function isEnrolledIn(Course $course): bool
    {
        return $this->enrollments()->where('course_id', $course->id)->exists();
    }

    /**
     * Get enrollment for a specific course.
     */
    public function getEnrollment(Course $course): ?Enrollment
    {
        return $this->enrollments()->where('course_id', $course->id)->first();
    }

    /**
     * Check if user has completed a lesson.
     */
    public function hasCompletedLesson(Lesson $lesson): bool
    {
        return $this->lessonProgress()->where('lesson_id', $lesson->id)->exists();
    }

    /**
     * Get progress percentage for a course.
     */
    public function getCourseProgress(Course $course): int
    {
        $totalLessons = $course->lessons()->count('lessons.id');
        
        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $this->lessonProgress()
            ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
            ->count();

        return (int) round(($completedLessons / $totalLessons) * 100);
    }

    /**
     * Get the last completed lesson for a course.
     */
    public function getLastCompletedLesson(Course $course): ?Lesson
    {
        $lastProgress = $this->lessonProgress()
            ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
            ->latest('completed_at')
            ->first();

        return $lastProgress?->lesson;
    }

    /**
     * Scope for students only.
     */
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    /**
     * Scope for admins only.
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }
}









