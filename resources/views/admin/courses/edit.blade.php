<x-admin-layout>
    <x-slot name="header">Edit Course</x-slot>

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Course Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="short_description" class="block text-sm font-medium text-slate-700 mb-2">Short Description</label>
                    <input type="text" name="short_description" id="short_description" value="{{ old('short_description', $course->short_description) }}" maxlength="500"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    @error('short_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Full Description</label>
                    <textarea name="description" id="description" rows="5"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">{{ old('description', $course->description) }}</textarea>
                </div>

                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-slate-700 mb-2">Thumbnail Image</label>
                    @if($course->thumbnail)
                        <div class="mb-3">
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="Current thumbnail" class="w-40 h-24 object-cover rounded-lg">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    <p class="mt-1 text-xs text-slate-500">Leave empty to keep current thumbnail</p>
                </div>

                <div>
                    <label for="difficulty" class="block text-sm font-medium text-slate-700 mb-2">Difficulty Level</label>
                    <select name="difficulty" id="difficulty" required
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        <option value="beginner" {{ old('difficulty', $course->difficulty) === 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('difficulty', $course->difficulty) === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('difficulty', $course->difficulty) === 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>

                <div>
                    <label for="what_you_learn" class="block text-sm font-medium text-slate-700 mb-2">What You'll Learn</label>
                    <textarea name="what_you_learn" id="what_you_learn" rows="4"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                        placeholder="Enter each learning outcome on a new line">{{ old('what_you_learn', is_array($course->what_you_learn) ? implode("\n", $course->what_you_learn) : '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Enter each item on a new line</p>
                </div>

                <div>
                    <label for="requirements" class="block text-sm font-medium text-slate-700 mb-2">Requirements</label>
                    <textarea name="requirements" id="requirements" rows="3"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                        placeholder="Enter each requirement on a new line">{{ old('requirements', is_array($course->requirements) ? implode("\n", $course->requirements) : '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Enter each item on a new line</p>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $course->is_published) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="is_published" class="ml-2 text-sm text-slate-700">Published</label>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                    Update Course
                </button>
                <a href="{{ route('admin.courses.show', $course) }}" class="px-6 py-2.5 text-slate-700 hover:text-slate-900 font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>












