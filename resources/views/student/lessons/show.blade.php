<x-app-layout>
    <x-slot name="title">{{ $lesson->title }} - RightPath LMS</x-slot>

    <div class="bg-slate-900 min-h-screen flex flex-col lg:flex-row">
        <!-- Sidebar -->
        <aside class="w-full lg:w-80 bg-slate-800 lg:h-screen lg:overflow-y-auto flex-shrink-0" x-data="{ showSidebar: false }">
            <div class="lg:hidden p-4 border-b border-slate-700">
                <button @click="showSidebar = !showSidebar" class="flex items-center text-white">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    Course Content
                </button>
            </div>
            
            <div class="hidden lg:block" :class="{ 'block': showSidebar, 'hidden': !showSidebar }">
                <div class="p-4 border-b border-slate-700">
                    <a href="{{ route('student.courses.show', $course) }}" class="text-sm text-slate-400 hover:text-white flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Course
                    </a>
                    <h2 class="text-white font-semibold line-clamp-2">{{ $course->title }}</h2>
                    <div class="mt-2">
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-1">
                            <span>Progress</span>
                            <span>{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-1.5">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
                
                <nav class="p-4 space-y-2">
                    @foreach($modules as $module)
                    <div x-data="{ open: {{ $module->id === $lesson->module_id ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="w-full flex items-center justify-between py-2 text-left {{ $module->is_available ? 'text-white' : 'text-slate-500' }}">
                            <span class="text-sm font-medium truncate">{{ $module->title }}</span>
                            <svg class="w-4 h-4 flex-shrink-0 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 space-y-1 ml-2">
                            @foreach($module->lessons as $l)
                            <li>
                                @if($module->is_available)
                                <a href="{{ route('student.lessons.show', ['course' => $course, 'lesson' => $l]) }}" 
                                   class="flex items-center py-2 px-3 rounded-lg text-sm {{ $l->id === $lesson->id ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                    @if($l->is_completed)
                                        <svg class="w-4 h-4 mr-2 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <span class="w-4 h-4 mr-2 flex-shrink-0 border border-slate-500 rounded-full"></span>
                                    @endif
                                    <span class="truncate">{{ $l->title }}</span>
                                </a>
                                @else
                                <span class="flex items-center py-2 px-3 text-sm text-slate-600">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="truncate">{{ $l->title }}</span>
                                </span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:h-screen lg:overflow-y-auto">
            <!-- Video Section -->
            @if($lesson->hasVideo())
            <div class="aspect-video bg-black relative" id="video-container">
                @if($lesson->hasUploadedVideo())
                    {{-- Protected HLS video player for uploaded videos --}}
                    @if($lesson->hls_processing)
                        {{-- Show processing message --}}
                        <div class="w-full h-full flex items-center justify-center text-white">
                            <div class="text-center">
                                <svg class="animate-spin h-12 w-12 mx-auto mb-4" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-lg">Processing video...</p>
                                <p class="text-sm text-slate-400 mt-2">This may take a few minutes. Please refresh the page shortly.</p>
                            </div>
                        </div>
                    @elseif($lesson->hls_path)
                        {{-- HLS Player --}}
                        <video 
                            id="protected-video"
                            class="w-full h-full"
                            controls
                            controlsList="nodownload noplaybackrate"
                            disablePictureInPicture
                            oncontextmenu="return false;"
                            playsinline
                        ></video>
                        {{-- Watermark overlay --}}
                        <div id="watermark" class="absolute pointer-events-none select-none" style="color: rgba(255,255,255,0.7); text-shadow: 2px 2px 4px rgba(0,0,0,0.8); font-size: 14px; font-weight: 600; z-index: 10;">
                            {{ auth()->user()->name }}<br>{{ auth()->user()->email }}
                        </div>
                    @else
                        {{-- Video queued for processing --}}
                        <div class="w-full h-full flex items-center justify-center text-white">
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto mb-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-lg">Video Processing Queued</p>
                                <p class="text-sm text-slate-400 mt-2">The video has been uploaded and will be processed shortly.</p>
                                <p class="text-sm text-slate-400">Please check back in a few minutes.</p>
                                <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-lg transition">
                                    Refresh Page
                                </button>
                            </div>
                        </div>
                    @endif
                @else
                    {{-- External video (YouTube, Vimeo, etc.) --}}
                    <iframe 
                        src="{{ $lesson->embed_url }}" 
                        class="w-full h-full" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                @endif
            </div>
            @endif

            <!-- Lesson Content -->
            <div class="bg-white">
                <div class="max-w-4xl mx-auto px-6 py-8">
                    <!-- Lesson Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm text-slate-500 mb-1">{{ $lesson->module->title }}</p>
                            <h1 class="text-2xl font-bold text-slate-900">{{ $lesson->title }}</h1>
                        </div>
                        
                        <!-- Complete Button -->
                        <form method="POST" action="{{ $isCompleted ? route('student.lessons.incomplete', $lesson) : route('student.lessons.complete', $lesson) }}">
                            @csrf
                            @if($isCompleted)
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 font-medium rounded-lg hover:bg-emerald-200 transition">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Completed
                                </button>
                            @else
                                <button type="submit" class="inline-flex items-center px-4 py-2 border-2 border-emerald-600 text-emerald-600 font-medium rounded-lg hover:bg-emerald-50 transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Mark Complete
                                </button>
                            @endif
                        </form>
                    </div>

                    <!-- Duration -->
                    @if($lesson->duration_minutes > 0)
                    <div class="flex items-center text-slate-500 text-sm mb-6">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $lesson->formatted_duration }}
                    </div>
                    @endif

                    <!-- Lesson Content -->
                    @if($lesson->content)
                    <div class="prose prose-slate max-w-none mb-8">
                        {!! $lesson->content !!}
                    </div>
                    @endif

                    <!-- Attachments -->
                    @if($lesson->hasAttachments())
                    <div class="mt-8 p-6 bg-slate-50 rounded-xl">
                        <h3 class="font-semibold text-slate-900 mb-4">Downloadable Resources</h3>
                        <ul class="space-y-2">
                            @foreach($lesson->attachments as $attachment)
                            <li>
                                <a href="{{ Storage::url($attachment['path']) }}" download class="flex items-center text-emerald-600 hover:text-emerald-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ $attachment['name'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Navigation -->
                    <div class="mt-8 pt-8 border-t border-slate-200 flex items-center justify-between">
                        @if($previousLesson)
                        <a href="{{ route('student.lessons.show', ['course' => $course, 'lesson' => $previousLesson]) }}" 
                           class="inline-flex items-center text-slate-600 hover:text-emerald-600 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Previous: {{ Str::limit($previousLesson->title, 30) }}
                        </a>
                        @else
                        <span></span>
                        @endif

                        @if($nextLesson)
                        <a href="{{ route('student.lessons.show', ['course' => $course, 'lesson' => $nextLesson]) }}" 
                           class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium">
                            Next: {{ Str::limit($nextLesson->title, 30) }}
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        @else
                        <a href="{{ route('student.courses.show', $course) }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium">
                            Back to Course
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    @if($lesson->hasUploadedVideo() && !$lesson->hls_processing)
    {{-- Video Protection Scripts --}}
    <style>
        /* Hide download button in video controls */
        video::-webkit-media-controls-download-button {
            display: none !important;
        }
        video::-webkit-media-controls-enclosure {
            overflow: hidden !important;
        }
        video::-webkit-media-controls-panel {
            width: calc(100% + 30px) !important;
        }
        /* Prevent text selection on video */
        #video-container {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        /* Watermark positioning */
        #watermark {
            transition: all 0.3s ease;
        }
    </style>

    @if($lesson->hls_path)
    {{-- HLS.js library --}}
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('protected-video');
            const container = document.getElementById('video-container');
            const watermark = document.getElementById('watermark');
            
            @if($lesson->hls_path)
            // Initialize HLS.js player
            if (video && Hls.isSupported()) {
                const hls = new Hls({
                    debug: false,
                    xhrSetup: function(xhr, url) {
                        xhr.withCredentials = false;
                    }
                });
                
                const playlistUrl = '{{ route("video.playlist", ["type" => "lesson", "id" => $lesson->id]) }}';
                console.log('Loading HLS playlist:', playlistUrl);
                
                hls.loadSource(playlistUrl);
                hls.attachMedia(video);
                
                hls.on(Hls.Events.MANIFEST_PARSED, function() {
                    console.log('HLS stream loaded successfully');
                });
                
                hls.on(Hls.Events.ERROR, function(event, data) {
                    console.error('HLS Error:', data);
                    
                    if (data.fatal) {
                        switch(data.type) {
                            case Hls.ErrorTypes.NETWORK_ERROR:
                                console.log('Network error, attempting recovery...');
                                setTimeout(() => hls.startLoad(), 1000);
                                break;
                            case Hls.ErrorTypes.MEDIA_ERROR:
                                console.log('Media error, attempting recovery...');
                                hls.recoverMediaError();
                                break;
                            default:
                                console.error('Unrecoverable error:', data);
                                hls.destroy();
                                // Show error message
                                const errorMsg = document.createElement('div');
                                errorMsg.className = 'absolute inset-0 flex items-center justify-center bg-black/80 text-white';
                                errorMsg.innerHTML = '<div class="text-center"><p class="text-lg mb-2">Unable to load video</p><p class="text-sm">Please refresh the page or contact support</p></div>';
                                container.appendChild(errorMsg);
                                break;
                        }
                    }
                });
                
                hls.on(Hls.Events.LEVEL_LOADED, function(event, data) {
                    console.log('Level loaded:', data);
                });
            } else if (video && video.canPlayType('application/vnd.apple.mpegurl')) {
                // Native HLS support (Safari)
                const playlistUrl = '{{ route("video.playlist", ["type" => "lesson", "id" => $lesson->id]) }}';
                console.log('Using native HLS support');
                video.src = playlistUrl;
                
                video.addEventListener('error', function(e) {
                    console.error('Video error:', e);
                });
            }
            @endif
            
            if (video) {
                // Disable right-click on video
                video.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    return false;
                });

                // Disable keyboard shortcuts
                document.addEventListener('keydown', function(e) {
                    // Disable Ctrl+S, Ctrl+Shift+S
                    if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S')) {
                        e.preventDefault();
                        return false;
                    }
                    // Disable F12 (dev tools)
                    if (e.key === 'F12') {
                        e.preventDefault();
                        return false;
                    }
                });

                // Disable drag
                video.addEventListener('dragstart', function(e) {
                    e.preventDefault();
                    return false;
                });
            }

            // Disable right-click on container
            if (container) {
                container.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    return false;
                });
            }

            // Dynamic watermark positioning
            if (watermark) {
                const positions = [
                    { top: '20px', left: '20px', right: 'auto', bottom: 'auto' },
                    { top: '20px', right: '20px', left: 'auto', bottom: 'auto' },
                    { bottom: '60px', left: '20px', top: 'auto', right: 'auto' },
                    { bottom: '60px', right: '20px', top: 'auto', left: 'auto' },
                    { top: '50%', left: '50%', right: 'auto', bottom: 'auto', transform: 'translate(-50%, -50%)' }
                ];
                
                let currentPosition = 0;
                
                function moveWatermark() {
                    currentPosition = (currentPosition + 1) % positions.length;
                    const pos = positions[currentPosition];
                    
                    watermark.style.top = pos.top;
                    watermark.style.left = pos.left;
                    watermark.style.right = pos.right;
                    watermark.style.bottom = pos.bottom;
                    watermark.style.transform = pos.transform || 'none';
                }
                
                // Initial position
                moveWatermark();
                
                // Move every 30 seconds
                setInterval(moveWatermark, 30000);
            }
        });
    </script>
    @endif
</x-app-layout>











