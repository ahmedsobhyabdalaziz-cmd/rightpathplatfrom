<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\LessonController as StudentLessonController;
use App\Http\Controllers\Student\ProgressController;
use App\Http\Controllers\Student\CertificateController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoStreamController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses', [HomeController::class, 'courses'])->name('courses.public');
Route::get('/courses/{course:slug}', [HomeController::class, 'showCourse'])->name('courses.show.public');

// Guest routes (authentication)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Student routes
    Route::prefix('learn')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentCourseController::class, 'dashboard'])->name('dashboard');
        Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
        Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])->name('courses.enroll');
        
        // Enrolled course routes
        Route::middleware('enrolled')->group(function () {
            Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
            Route::get('/courses/{course}/lessons/{lesson}', [StudentLessonController::class, 'show'])->name('lessons.show');
            Route::post('/lessons/{lesson}/complete', [ProgressController::class, 'markComplete'])->name('lessons.complete');
            Route::delete('/lessons/{lesson}/complete', [ProgressController::class, 'markIncomplete'])->name('lessons.incomplete');
        });

        // Certificates
        Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
        Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
    });

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Course management
        Route::resource('courses', AdminCourseController::class);
        
        // Module management (nested under courses)
        Route::resource('courses.modules', AdminModuleController::class)->shallow();
        Route::post('modules/reorder', [AdminModuleController::class, 'reorder'])->name('modules.reorder');
        
        // Lesson management (nested under modules)
        Route::resource('modules.lessons', AdminLessonController::class)->shallow();
        Route::post('lessons/reorder', [AdminLessonController::class, 'reorder'])->name('lessons.reorder');
        
        // Students management
        Route::get('/students', [AdminDashboardController::class, 'students'])->name('students.index');
        Route::get('/students/{user}', [AdminDashboardController::class, 'showStudent'])->name('students.show');
    });
});

// Certificate verification (public)
Route::get('/verify/{certificate_number}', [CertificateController::class, 'verify'])->name('certificates.verify');

// Protected video streaming (signed URLs)
Route::get('/video/stream/{type}/{id}', [VideoStreamController::class, 'stream'])
    ->name('video.stream')
    ->middleware(['auth', 'signed']);

// HLS video streaming
Route::get('/video/hls/{type}/{id}/playlist.m3u8', [VideoStreamController::class, 'playlist'])
    ->name('video.playlist')
    ->middleware(['auth', 'signed']);

Route::get('/video/hls/{type}/{id}/key', [VideoStreamController::class, 'keyDelivery'])
    ->name('video.key')
    ->middleware(['auth', 'signed']);

Route::get('/video/hls/{type}/{id}/{segment}', [VideoStreamController::class, 'segment'])
    ->name('video.segment')
    ->middleware(['auth', 'signed']);









