<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class VideoEncryptionKey extends Model
{
    protected $fillable = [
        'video_type',
        'video_id',
        'encryption_key',
    ];

    /**
     * Get the encryption key (automatically encrypted/decrypted).
     */
    protected function encryptionKey(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => decrypt($value),
            set: fn ($value) => encrypt($value),
        );
    }
}
