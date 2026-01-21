<?php

namespace App\Services;

use App\Models\VideoEncryptionKey;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HlsService
{
    /**
     * Convert a video to HLS format with AES-128 encryption.
     *
     * @param string $videoPath Path to the original video in storage
     * @param string $videoType Type of video ('lesson' or 'module')
     * @param int $videoId ID of the lesson or module
     * @return array ['success' => bool, 'hls_path' => string|null, 'key_id' => int|null, 'error' => string|null]
     */
    public function convertToHls(string $videoPath, string $videoType, int $videoId): array
    {
        try {
            $disk = Storage::disk(config('video.storage_disk', 'local'));
            
            if (!$disk->exists($videoPath)) {
                return ['success' => false, 'error' => 'Video file not found'];
            }

            // Generate encryption key (16 bytes for AES-128)
            $encryptionKey = random_bytes(16);
            $encryptionKeyHex = bin2hex($encryptionKey);
            
            // Store encryption key in database
            $keyRecord = VideoEncryptionKey::create([
                'video_type' => $videoType,
                'video_id' => $videoId,
                'encryption_key' => $encryptionKeyHex,
            ]);

            // Create output directory for HLS segments
            $hlsDir = "hls/{$videoType}s/{$videoId}";
            $hlsDirPath = $disk->path($hlsDir);
            
            if (!file_exists($hlsDirPath)) {
                mkdir($hlsDirPath, 0755, true);
            }

            // Write encryption key to temporary file
            $keyFile = $hlsDirPath . '/enc.key';
            file_put_contents($keyFile, $encryptionKey);

            // Create key info file for ffmpeg
            $keyInfoFile = $hlsDirPath . '/enc.keyinfo';
            $keyInfoContent = implode("\n", [
                'enc.key', // Key URI (will be replaced in m3u8)
                $keyFile,  // Path to key file
                $encryptionKeyHex, // IV (initialization vector)
            ]);
            file_put_contents($keyInfoFile, $keyInfoContent);

            // Get full path to original video
            $inputPath = $disk->path($videoPath);
            $outputPlaylist = $hlsDirPath . '/playlist.m3u8';

            // FFmpeg command to convert to HLS with encryption
            $command = sprintf(
                'ffmpeg -i %s -codec: copy -start_number 0 -hls_time 10 -hls_list_size 0 ' .
                '-hls_key_info_file %s -hls_playlist_type vod -hls_segment_filename %s ' .
                '-f hls %s 2>&1',
                escapeshellarg($inputPath),
                escapeshellarg($keyInfoFile),
                escapeshellarg($hlsDirPath . '/segment_%03d.ts'),
                escapeshellarg($outputPlaylist)
            );

            exec($command, $output, $returnCode);

            // Clean up temporary key files
            @unlink($keyFile);
            @unlink($keyInfoFile);

            if ($returnCode !== 0) {
                Log::error('FFmpeg HLS conversion failed', [
                    'command' => $command,
                    'output' => $output,
                    'return_code' => $returnCode,
                ]);
                return ['success' => false, 'error' => 'FFmpeg conversion failed: ' . implode("\n", $output)];
            }

            // Modify playlist to use signed URL for key delivery
            $this->updatePlaylistKeyUri($outputPlaylist, $videoType, $videoId);

            return [
                'success' => true,
                'hls_path' => $hlsDir . '/playlist.m3u8',
                'key_id' => $keyRecord->id,
                'error' => null,
            ];

        } catch (\Exception $e) {
            Log::error('HLS conversion exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Update the m3u8 playlist to use our key delivery endpoint.
     */
    protected function updatePlaylistKeyUri(string $playlistPath, string $videoType, int $videoId): void
    {
        $content = file_get_contents($playlistPath);
        
        // Replace enc.key with a placeholder that will be replaced at runtime
        $content = str_replace(
            'URI="enc.key"',
            'URI="__KEY_URL_PLACEHOLDER__"',
            $content
        );
        
        file_put_contents($playlistPath, $content);
    }

    /**
     * Delete HLS files for a video.
     */
    public function deleteHls(string $hlsPath): void
    {
        try {
            $disk = Storage::disk(config('video.storage_disk', 'local'));
            
            // Get directory from playlist path
            $directory = dirname($hlsPath);
            
            if ($disk->exists($directory)) {
                $disk->deleteDirectory($directory);
            }
        } catch (\Exception $e) {
            Log::error('Failed to delete HLS files', [
                'path' => $hlsPath,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the m3u8 playlist content with actual key URL injected.
     */
    public function getPlaylistWithKeyUrl(string $hlsPath, string $keyUrl): string
    {
        $disk = Storage::disk(config('video.storage_disk', 'local'));
        $content = $disk->get($hlsPath);
        
        // Replace placeholder with actual signed key URL
        $content = str_replace('__KEY_URL_PLACEHOLDER__', $keyUrl, $content);
        
        return $content;
    }
}

