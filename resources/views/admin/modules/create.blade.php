<x-admin-layout>
    <x-slot name="header">Add Module to {{ $course->title }}</x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.courses.modules.store', $course) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Module Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 @error('title') border-red-500 @enderror"
                        placeholder="e.g., Getting Started">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                        placeholder="Brief description of what this module covers">{{ old('description') }}</textarea>
                </div>

                <!-- Video Section -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4">Module Video (Optional)</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="video_type" class="block text-sm font-medium text-slate-700 mb-2">Video Type</label>
                            <select name="video_type" id="video_type" x-data x-on:change="$dispatch('video-type-changed', $el.value)"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                <option value="none" {{ old('video_type', 'none') === 'none' ? 'selected' : '' }}>No Video</option>
                                <option value="upload" {{ old('video_type') === 'upload' ? 'selected' : '' }}>Upload Video File</option>
                                <option value="youtube" {{ old('video_type') === 'youtube' ? 'selected' : '' }}>YouTube URL</option>
                                <option value="vimeo" {{ old('video_type') === 'vimeo' ? 'selected' : '' }}>Vimeo URL</option>
                                <option value="custom" {{ old('video_type') === 'custom' ? 'selected' : '' }}>Custom URL</option>
                            </select>
                        </div>

                        <div x-data="{ videoType: '{{ old('video_type', 'none') }}' }" 
                             x-on:video-type-changed.window="videoType = $event.detail"
                             class="space-y-4">
                            
                            <!-- Upload Field -->
                            <div x-show="videoType === 'upload'" x-cloak>
                                <label for="video_file" class="block text-sm font-medium text-slate-700 mb-2">Video File</label>
                                <input type="file" name="video_file" id="video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime"
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                <p class="mt-1 text-xs text-slate-500">Supported formats: MP4, WebM, OGG, MOV. Max size: 500MB</p>
                                @error('video_file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- URL Field -->
                            <div x-show="videoType === 'youtube' || videoType === 'vimeo' || videoType === 'custom'" x-cloak>
                                <label for="video_url" class="block text-sm font-medium text-slate-700 mb-2">Video URL</label>
                                <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                                    placeholder="https://www.youtube.com/watch?v=...">
                                @error('video_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Duration Field -->
                            <div x-show="videoType !== 'none'" x-cloak>
                                <label for="video_duration_minutes" class="block text-sm font-medium text-slate-700 mb-2">Video Duration (minutes)</label>
                                <input type="number" name="video_duration_minutes" id="video_duration_minutes" value="{{ old('video_duration_minutes', 0) }}" min="0"
                                    class="w-full md:w-48 px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="drip_days" class="block text-sm font-medium text-slate-700 mb-2">Drip Content (Days)</label>
                    <input type="number" name="drip_days" id="drip_days" value="{{ old('drip_days', 0) }}" min="0"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    <p class="mt-1 text-xs text-slate-500">Number of days after enrollment before this module unlocks. Set to 0 for immediate access.</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                    Create Module
                </button>
                <a href="{{ route('admin.courses.show', $course) }}" class="px-6 py-2.5 text-slate-700 hover:text-slate-900 font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>









