<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Course;

class CheckEnrollment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $course = $request->route('course');
        
        if ($course instanceof Course) {
            $courseId = $course->id;
        } else {
            $courseId = $course;
        }

        $user = auth()->user();

        // Allow admins to access any course
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user is enrolled in this course
        if (!$user->enrollments()->where('course_id', $courseId)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        return $next($request);
    }
}











