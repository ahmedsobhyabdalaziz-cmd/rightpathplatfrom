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
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('hls_path')->nullable()->after('video_path');
            $table->foreignId('hls_key_id')->nullable()->after('hls_path')->constrained('video_encryption_keys')->nullOnDelete();
            $table->boolean('hls_processing')->default(false)->after('hls_key_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['hls_key_id']);
            $table->dropColumn(['hls_path', 'hls_key_id', 'hls_processing']);
        });
    }
};
