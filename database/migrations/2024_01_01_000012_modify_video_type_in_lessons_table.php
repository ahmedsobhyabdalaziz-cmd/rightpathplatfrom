<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table to change enum values
        if (DB::getDriverName() === 'sqlite') {
            // Disable foreign key checks
            DB::statement('PRAGMA foreign_keys=OFF');
            
            // Rename the old table
            DB::statement('ALTER TABLE lessons RENAME TO lessons_old');
            
            // Drop the old index first
            DB::statement('DROP INDEX IF EXISTS lessons_module_id_order_index');
            
            // Create new table with updated video_type
            Schema::create('lessons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('module_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->longText('content')->nullable();
                $table->string('video_url')->nullable();
                $table->string('video_type')->default('none'); // Changed to string for flexibility
                $table->string('video_path')->nullable();
                $table->json('attachments')->nullable();
                $table->integer('duration_minutes')->default(0);
                $table->integer('order')->default(0);
                $table->boolean('is_free_preview')->default(false);
                $table->timestamps();
                
                $table->index(['module_id', 'order']);
            });
            
            // Copy data from old table
            DB::statement('INSERT INTO lessons SELECT id, module_id, title, description, content, video_url, video_type, video_path, attachments, duration_minutes, "order", is_free_preview, created_at, updated_at FROM lessons_old');
            
            // Drop old table
            DB::statement('DROP TABLE lessons_old');
            
            // Re-enable foreign key checks
            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            // For MySQL/PostgreSQL, we can alter the column directly
            DB::statement("ALTER TABLE lessons MODIFY COLUMN video_type ENUM('youtube', 'vimeo', 'custom', 'upload', 'none') DEFAULT 'none'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // For SQLite, recreate the table without 'upload' option
            DB::statement('PRAGMA foreign_keys=OFF');
            DB::statement('ALTER TABLE lessons RENAME TO lessons_old');
            
            Schema::create('lessons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('module_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->longText('content')->nullable();
                $table->string('video_url')->nullable();
                $table->enum('video_type', ['youtube', 'vimeo', 'custom', 'none'])->default('none');
                $table->string('video_path')->nullable();
                $table->json('attachments')->nullable();
                $table->integer('duration_minutes')->default(0);
                $table->integer('order')->default(0);
                $table->boolean('is_free_preview')->default(false);
                $table->timestamps();
                
                $table->index(['module_id', 'order']);
            });
            
            DB::statement('INSERT INTO lessons SELECT * FROM lessons_old WHERE video_type != \'upload\'');
            DB::statement('DROP TABLE lessons_old');
            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            DB::statement("ALTER TABLE lessons MODIFY COLUMN video_type ENUM('youtube', 'vimeo', 'custom', 'none') DEFAULT 'none'");
        }
    }
};

