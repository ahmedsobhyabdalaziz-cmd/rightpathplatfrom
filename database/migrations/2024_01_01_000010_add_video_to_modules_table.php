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
        Schema::table('modules', function (Blueprint $table) {
            $table->string('video_path')->nullable()->after('description');
            $table->string('video_type')->default('none')->after('video_path'); // none, upload, youtube, vimeo, custom
            $table->string('video_url')->nullable()->after('video_type');
            $table->integer('video_duration_minutes')->default(0)->after('video_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'video_type', 'video_url', 'video_duration_minutes']);
        });
    }
};


