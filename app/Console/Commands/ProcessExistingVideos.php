<?php

namespace App\Console\Commands;

use App\Models\Lesson;
use App\Models\Module;
use App\Jobs\ConvertToHls;
use Illuminate\Console\Command;

class ProcessExistingVideos extends Command
{
    protected $signature = 'videos:process-existing 
                            {--dry-run : Show what would be processed without actually processing}';

    protected $description = 'Queue HLS conversion for existing uploaded videos that haven\'t been processed yet';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Checking for existing videos that need HLS processing...');
        $this->newLine();

        // Check lessons
        $lessons = Lesson::where('video_type', 'upload')
            ->whereNotNull('video_path')
            ->whereNull('hls_path')
            ->where('hls_processing', false)
            ->get();

        // Check modules
        $modules = Module::where('video_type', 'upload')
            ->whereNotNull('video_path')
            ->whereNull('hls_path')
            ->where('hls_processing', false)
            ->get();

        $this->info("Found {$lessons->count()} lessons and {$modules->count()} modules with videos to process");
        $this->newLine();

        if ($dryRun) {
            if ($lessons->count() > 0) {
                $this->info('Lessons that would be processed:');
                foreach ($lessons as $lesson) {
                    $this->line("  - Lesson #{$lesson->id}: {$lesson->title}");
                    $this->line("    Video: {$lesson->video_path}");
                }
                $this->newLine();
            }

            if ($modules->count() > 0) {
                $this->info('Modules that would be processed:');
                foreach ($modules as $module) {
                    $this->line("  - Module #{$module->id}: {$module->title}");
                    $this->line("    Video: {$module->video_path}");
                }
                $this->newLine();
            }

            $this->warn('This is a dry run. Use without --dry-run to actually queue the conversions.');
            return 0;
        }

        // Process lessons
        foreach ($lessons as $lesson) {
            $this->info("Queuing lesson #{$lesson->id}: {$lesson->title}");
            ConvertToHls::dispatch('lesson', $lesson->id, $lesson->video_path);
        }

        // Process modules
        foreach ($modules as $module) {
            $this->info("Queuing module #{$module->id}: {$module->title}");
            ConvertToHls::dispatch('module', $module->id, $module->video_path);
        }

        $total = $lessons->count() + $modules->count();
        
        if ($total > 0) {
            $this->newLine();
            $this->success("Queued {$total} videos for HLS conversion!");
            $this->info('The cron job will process them automatically every minute.');
            $this->info('Or run manually: php artisan queue:work --stop-when-empty');
        } else {
            $this->info('No videos found that need processing.');
        }

        return 0;
    }

    protected function success($message)
    {
        $this->line("<info>✓</info> {$message}");
    }
}

