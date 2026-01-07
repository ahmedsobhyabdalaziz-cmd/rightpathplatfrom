<x-admin-layout>
    <x-slot name="header">Add Lesson to {{ $module->title }}</x-slot>

    <div class="max-w-3xl" x-data="{ videoType: '{{ old('video_type', 'none') }}' }">
        <form method="POST" action="{{ route('admin.modules.lessons.store', $module) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Lesson Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 @error('title') border-red-500 @enderror"
                        placeholder="e.g., Introduction to Variables">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="2"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                        placeholder="Brief description of this lesson">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-slate-700 mb-2">Lesson Content</label>
                    <textarea name="content" id="content" rows="8"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                        placeholder="Main content of the lesson (supports HTML)">{{ old('content') }}</textarea>
                </div>

                <!-- Video Section -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Video Content</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="video_type" class="block text-sm font-medium text-slate-700 mb-2">Video Type</label>
                            <select name="video_type" id="video_type" x-model="videoType"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                <option value="none">No Video</option>
                                <option value="upload">Upload Video File</option>
                                <option value="youtube">YouTube URL</option>
                                <option value="vimeo">Vimeo URL</option>
                                <option value="custom">Custom Video URL</option>
                            </select>
                        </div>

                        <div x-show="videoType === 'upload'" x-cloak>
                            <label for="video_file" class="block text-sm font-medium text-slate-700 mb-2">Upload Video File</label>
                            <input type="file" name="video_file" id="video_file" accept="video/mp4,video/webm,video/ogg,video/mov"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                            <p class="mt-1 text-xs text-slate-500">Max 500MB. Supported formats: MP4, WebM, OGG, MOV</p>
                            @error('video_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-show="['youtube', 'vimeo', 'custom'].includes(videoType)" x-cloak>
                            <label for="video_url" class="block text-sm font-medium text-slate-700 mb-2">Video URL</label>
                            <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                                placeholder="https://www.youtube.com/watch?v=...">
                            @error('video_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-slate-700 mb-2">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', 0) }}" min="0"
                        class="w-full md:w-48 px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                </div>

                <div>
                    <label for="attachments" class="block text-sm font-medium text-slate-700 mb-2">Attachments</label>
                    <input type="file" name="attachments[]" id="attachments" multiple
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    <p class="mt-1 text-xs text-slate-500">Upload downloadable resources (max 10MB each)</p>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_free_preview" id="is_free_preview" value="1" {{ old('is_free_preview') ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="is_free_preview" class="ml-2 text-sm text-slate-700">Allow free preview (visible to non-enrolled users)</label>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                    Create Lesson
                </button>
                <a href="{{ route('admin.courses.show', $module->course) }}" class="px-6 py-2.5 text-slate-700 hover:text-slate-900 font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>









