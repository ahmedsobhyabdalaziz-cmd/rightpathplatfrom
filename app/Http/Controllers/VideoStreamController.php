<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;

class VideoStreamController extends Controller
{
    /**
     * Stream a protected video.
     *
     * @param Request $request
     * @param string $type Type of content (lesson or module)
     * @param int $id The ID of the content
     * @return StreamedResponse|Response
     */
    public function stream(Request $request, string $type, int $id)
    {
        // Validate type
        if (!in_array($type, ['lesson', 'module'])) {
            abort(404, 'Invalid video type.');
        }

        // Get the model and video path
        $model = $this->getModel($type, $id);
        
        if (!$model) {
            abort(404, 'Content not found.');
        }

        // Check enrollment for lessons
        if ($type === 'lesson') {
            $course = $model->module->course;
            if (!$this->userIsEnrolled($course)) {
                abort(403, 'You must be enrolled in this course to view this video.');
            }
        }

        // Check enrollment for modules
        if ($type === 'module') {
            $course = $model->course;
            if (!$this->userIsEnrolled($course)) {
                abort(403, 'You must be enrolled in this course to view this video.');
            }
        }

        // Get video path
        $videoPath = $model->video_path;
        
        if (!$videoPath) {
            abort(404, 'Video not found.');
        }

        // Get the storage disk
        $disk = Storage::disk(config('video.storage_disk', 'local'));
        
        if (!$disk->exists($videoPath)) {
            abort(404, 'Video file not found.');
        }

        // Get file info
        $fileSize = $disk->size($videoPath);
        $mimeType = $disk->mimeType($videoPath) ?? 'video/mp4';

        // Validate mime type
        $allowedTypes = config('video.allowed_mime_types', ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime']);
        if (!in_array($mimeType, $allowedTypes)) {
            abort(403, 'Invalid video format.');
        }

        // Handle range requests for video seeking
        $start = 0;
        $end = $fileSize - 1;
        $statusCode = 200;

        if ($request->hasHeader('Range')) {
            $statusCode = 206;
            $range = $request->header('Range');
            
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = intval($matches[1]);
                if (!empty($matches[2])) {
                    $end = intval($matches[2]);
                }
            }
        }

        $length = $end - $start + 1;

        // Create streaming response
        $response = new StreamedResponse(function () use ($disk, $videoPath, $start, $length) {
            $stream = $disk->readStream($videoPath);
            
            if ($start > 0) {
                fseek($stream, $start);
            }

            $chunkSize = config('video.chunk_size', 1024 * 1024);
            $bytesRemaining = $length;

            while ($bytesRemaining > 0 && !feof($stream)) {
                $bytesToRead = min($chunkSize, $bytesRemaining);
                $data = fread($stream, $bytesToRead);
                
                if ($data === false) {
                    break;
                }
                
                echo $data;
                flush();
                
                $bytesRemaining -= strlen($data);
            }

            fclose($stream);
        }, $statusCode);

        // Set headers to prevent caching and downloading
        $response->headers->set('Content-Type', $mimeType);
        $response->headers->set('Content-Length', $length);
        $response->headers->set('Accept-Ranges', 'bytes');
        $response->headers->set('Content-Range', "bytes {$start}-{$end}/{$fileSize}");
        
        // Security headers to discourage downloading
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('Content-Disposition', 'inline'); // Prevent download prompt
        
        // Prevent embedding on other sites
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        return $response;
    }

    /**
     * Get the model based on type and ID.
     */
    protected function getModel(string $type, int $id)
    {
        return match ($type) {
            'lesson' => Lesson::with('module.course')->find($id),
            'module' => Module::with('course')->find($id),
            default => null,
        };
    }

    /**
     * Check if the current user is enrolled in the course.
     */
    protected function userIsEnrolled($course): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        // Admins can always view
        if ($user->isAdmin()) {
            return true;
        }

        return $user->enrollments()->where('course_id', $course->id)->exists();
    }
}

