<style>
    /* Add a loading effect on the video container */
    .video-tool {
        position: relative;
        background: url('your-loading-image.gif') no-repeat center center;
        background-size: 50%;
        min-height: 400px; /* Adjust based on your layout */
    }

    /* Remove the preload effect once the video is ready */
    .video-tool.video-loaded {
        background: none;
    }

    /* The rest of your existing styles */
    .play-title {
        color: white; /* Set text color to white */
        background-color: black; /* Set background to black */
        padding: 10px; /* Add some padding around the text */
        border-radius: 5px; /* Smooth corners */
        font-family: Arial, sans-serif; /* Use a clean and readable font */
        text-align: center; /* Center the text */
        font-size: 1.5rem; /* Adjust the font size */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3); /* Add a subtle shadow for depth */
        transition: background-color 0.3s ease; /* Smooth transition for hover effect */
    }

    .play-title:hover {
        background-color: #333; /* Change the background color when hovered */
    }

    .video-time-tracker {
        color: #fff;
        font-size: 1rem;
        font-weight: bold;
        text-align: center;
        margin-top: 10px;
        background: #222;
        padding: 8px 16px;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(255, 255, 255, 0.1);
        display: inline-block;
    }

    /* Style for all buttons */
    .btn {
        font-size: 16px;
        padding: 10px 15px;
        border-radius: 5px;
        text-transform: capitalize;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-sm {
        font-size: 14px;
        padding: 5px 10px;
    }

    .btn:hover {
        opacity: 0.8;
        transform: scale(1.1);
    }

    /* Play/Pause Button */
    #play-pause {
        background-color: #007bff;
        color: white;
    }

    #play-pause:hover {
        background-color: #0056b3;
    }

    /* Mute/Unmute Button */
    #mute-unmute {
        background-color: #ffc107;
        color: white;
    }

    #mute-unmute:hover {
        background-color: #e0a800;
    }

    /* Fullscreen Button */
    #fullscreen {
        background-color: #17a2b8;
        color: white;
    }

    #fullscreen:hover {
        background-color: #138496;
    }

    /* Skip Forward Button */
    #skip-forward {
        background-color: #28a745;
        color: white;
    }

    #skip-forward:hover {
        background-color: #218838;
    }

    /* Rewind Button */
    #skip-backward {
        background-color: #dc3545;
        color: white;
    }

    #skip-backward:hover {
        background-color: #c82333;
    }

    .animated-bg-wrapper {
        position: relative;
        overflow: hidden;
        padding: 30px;
        border-radius: 12px;
        z-index: 0;
        color: white;
    }

    /* Animated gradient background */
    .animated-bg-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, #ff6ec4, #7873f5, #4ADEDE, #CBBACC);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        z-index: -2;
        filter: blur(40px);
    }

    /* Dark overlay to improve readability */
    .animated-bg-wrapper .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* adjust for text contrast */
        z-index: -1;
        border-radius: 12px;
    }

    /* Text styling */
    .play-title {
        position: relative;
        font-size: 1.5rem;
        line-height: 1.4;
        z-index: 1;
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,0.6);
    }

    /* Gradient Animation Keyframes */
    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
</style>

@if($play)
    @php
        if ($play->episode_number >= 2) {
            $currentPlayRoute = route('courses.show', ['slug' => $course->slug, 'epi' => $play->slug]);
        } else {
            $currentPlayRoute = route('courses.show.rest', ['slug' => $course->slug]);
        }
    @endphp
    <div class="col-xxl-7 col-xl-7 col-lg-8 col-md-12 order-1" id="scrollHere">
        <br>

        <div class="animated-bg-wrapper">
            <div class="overlay"></div>
            <a href="{{ $currentPlayRoute }}">
                <h3 class="play-title">
                    <span class="episode-number">{{ $play->episode_number }}</span>
                    <br>
                    {{ \Str::title($play->title) }}
                </h3>
            </a>
        </div>

        <div class="video-tool py-4" id="videoTool">
            <video id="my-video" class="video-js vjs-default-skin" controls preload="auto"
                   width="100%" height="auto" poster="loading-poster.jpg">
                <source src="{{ $play->video_path }}" type="video/mp4">
                <p class="vjs-no-js">
                    To view this video please enable JavaScript, and consider upgrading to a web browser that
                    <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                </p>
            </video>

            <script>
                var player = videojs('my-video', {
                    html5: {
                        nativeTextTracks: false,
                        hls: {
                            overrideNative: true
                        }
                    }
                });

                player.on('error', function() {
                    var error = player.error();
                    console.error('Video Error:', error);
                    // Show error message to user
                });
            </script>

            <div class="d-flex align-items-center gap-3 mt-3 justify-content-center">
                <div id="timeTracker" class="video-time-tracker text-white fw-semibold">
                    ⏱ 00:00 / 00:00
                </div>
            </div>
        </div>

        <div class="container py-3">
            <div class="d-flex flex-column flex-md-row flex-wrap justify-content-center gap-2 py-3">
                <!-- Play/Pause Button -->
                <button id="play-pause" class="btn btn-primary btn-sm px-3">
                    ▶️ Play
                </button>

                <!-- Mute/Unmute Button -->
                <button id="mute-unmute" class="btn btn-warning btn-sm px-3">
                    🔊 Mute
                </button>

                <!-- Full Screen Button -->
                <button id="fullscreen" class="btn btn-info btn-sm px-3">
                    🖥️ Fullscreen
                </button>

                <!-- Skip 10 Seconds Button -->
                <button id="skip-forward" class="btn btn-success btn-sm px-3">
                    ⏩ 10s Forward
                </button>

                <!-- Rewind 10 Seconds Button -->
                <button id="skip-backward" class="btn btn-danger btn-sm px-3">
                    ⏪ 10s Back
                </button>

                <!-- Picture-in-Picture -->
                <button id="pip" class="btn btn-info btn-sm px-3">
                    🖼️ Picture-in-Picture
                </button>

                <!-- Toggle Subtitles -->
                <div class="dropdown">
                    <button class="btn btn-warning btn-sm dropdown-toggle px-3" type="button" id="subtitleDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        🔠 Subtitles
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="subtitleDropdown">
                        <li><a class="dropdown-item subtitle-option" data-lang="off" href="#">❌ Off</a></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Like & Dislike Buttons --}}
{{--        <div class="d-flex justify-content-center gap-4 py-3">--}}
{{--            <form method="POST" action="{{ route('frt.crs.epi.like', $play->id) }}">--}}
{{--                @csrf--}}
{{--                <button type="submit" class="btn btn-success btn-lg">--}}
{{--                    <i class="bi bi-hand-thumbs-up-fill"></i> Like--}}
{{--                </button>--}}
{{--            </form>--}}

{{--            <form method="POST" action="{{ route('frt.crs.epi.dislike', $play->id) }}">--}}
{{--                @csrf--}}
{{--                <button type="submit" class="btn btn-danger btn-lg">--}}
{{--                    <i class="bi bi-hand-thumbs-down-fill"></i> Dislike--}}
{{--                </button>--}}
{{--            </form>--}}
{{--        </div>--}}

        @php
            $total = $likes + $dislikes;
            $likesPercent = $total > 0 ? ($likes / $total) * 100 : 0;
            $dislikesPercent = $total > 0 ? ($dislikes / $total) * 100 : 0;
        @endphp

        <div class="w-75 mx-auto mt-4">
            <div class="progress position-relative" style="height: 30px; background-color: #444; border-radius: 5px;">
                @if($likes > 0)
                    <div class="progress-bar bg-success"
                         style="width: {{ $likesPercent }}%; border-top-left-radius: 5px; border-bottom-left-radius: 5px;">
                    </div>
                @endif

                @if($dislikes > 0)
                    <div class="progress-bar bg-danger"
                         style="width: {{ $dislikesPercent }}%; @if($likes == 0) border-top-left-radius: 5px; border-bottom-left-radius: 5px; @endif border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
                    </div>
                @endif

                <div class="position-absolute w-100 h-100 d-flex justify-content-between align-items-center px-3 text-white fw-bold">
                    <span>👍 {{ $likes }}</span>
                    <span>👎 {{ $dislikes }}</span>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="col-xxl-7 col-xl-7 col-lg-8 col-md-12 order-3 order-sm-3 order-md-3 order-lg-2">
        <div class="video-tool py-4">
            <p style="color:white">
                No episode has been added yet to this course
            </p>
        </div>
    </div>
@endif

<script>
    // Remove preload effect when the video can play
    function removePreloadEffect() {
        document.getElementById('videoTool').classList.add('video-loaded');
    }
</script>
