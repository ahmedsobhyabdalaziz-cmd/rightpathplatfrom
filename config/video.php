<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Video URL Expiration Time
    |--------------------------------------------------------------------------
    |
    | This value determines how long signed video URLs remain valid.
    | The value is specified in minutes. After this time, users will
    | need to refresh the page to get a new signed URL.
    |
    */
    'url_expiry_minutes' => env('VIDEO_URL_EXPIRY_MINUTES', 15),

    /*
    |--------------------------------------------------------------------------
    | Video Storage Disk
    |--------------------------------------------------------------------------
    |
    | The disk where protected videos are stored. This should be a
    | non-public disk to prevent direct URL access.
    |
    */
    'storage_disk' => env('VIDEO_STORAGE_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Allowed Video Mime Types
    |--------------------------------------------------------------------------
    |
    | The mime types that are allowed for video streaming.
    |
    */
    'allowed_mime_types' => [
        'video/mp4',
        'video/webm',
        'video/ogg',
        'video/quicktime',
    ],

    /*
    |--------------------------------------------------------------------------
    | Chunk Size for Streaming
    |--------------------------------------------------------------------------
    |
    | The size of chunks (in bytes) when streaming video content.
    | Default is 1MB.
    |
    */
    'chunk_size' => env('VIDEO_CHUNK_SIZE', 1024 * 1024),
];


