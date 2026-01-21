<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Models\Module;
use App\Services\HlsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ConvertToHls implements ShouldQueue
{
    use Queueable;

    public $timeout = 3600; // 1 hour timeout for large videos
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $videoType,
        public int $videoId,
        public string $videoPath
    ) {}

    /**
     * Execute the job.
     */
    public function handle(HlsService $hlsService): void
    {
        Log::info("Starting HLS conversion for {$this->videoType} {$this->videoId}");

        // Get the model
        $model = $this->getModel();
        
        if (!$model) {
            Log::error("Model not found for HLS conversion: {$this->videoType} {$this->videoId}");
            return;
        }

        // Mark as processing
        $model->update(['hls_processing' => true]);

        // Convert to HLS
        $result = $hlsService->convertToHls($this->videoPath, $this->videoType, $this->videoId);

        if ($result['success']) {
            // Update model with HLS path and key
            $model->update([
                'hls_path' => $result['hls_path'],
                'hls_key_id' => $result['key_id'],
                'hls_processing' => false,
            ]);

            Log::info("HLS conversion completed for {$this->videoType} {$this->videoId}");

            // Optionally delete original video to save space
            // Storage::disk(config('video.storage_disk', 'local'))->delete($this->videoPath);
        } else {
            Log::error("HLS conversion failed for {$this->videoType} {$this->videoId}: {$result['error']}");
            
            $model->update(['hls_processing' => false]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("HLS conversion job failed for {$this->videoType} {$this->videoId}", [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        $model = $this->getModel();
        if ($model) {
            $model->update(['hls_processing' => false]);
        }
    }

    /**
     * Get the model instance.
     */
    protected function getModel()
    {
        return match ($this->videoType) {
            'lesson' => Lesson::find($this->videoId),
            'module' => Module::find($this->videoId),
            default => null,
        };
    }
}
