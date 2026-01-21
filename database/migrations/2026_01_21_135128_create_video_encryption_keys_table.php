<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('video_encryption_keys', function (Blueprint $table) {
            $table->id();
            $table->string('video_type'); // 'lesson' or 'module'
            $table->unsignedBigInteger('video_id');
            $table->text('encryption_key'); // Will be encrypted using Laravel encryption
            $table->timestamps();
            
            // Composite index for fast lookups
            $table->index(['video_type', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_encryption_keys');
    }
};
