@extends('courses.player.master')

@php
$fullTitle = $course->title . " - " . $play->title;
$likes = $play->likes($play->id);
$dislikes = $play->dislikes($play->id);
$totalVotes = $likes + $dislikes;
@endphp


@section('title',$fullTitle)

@section('styles')
<style>
    .episode-icon i {
        font-size: 32px;
        color: #007bff;
        transition: transform 0.3s ease, color 0.3s ease;
        cursor: pointer;
    }

    .episode-icon i:hover {
        color: #0056b3;
        transform: scale(1.1);
    }
    #friends {
        padding: 20px;
        background-color: #1a1a1a; /* Dark background for contrast */
        border-radius: 8px; /* Slight rounding of corners for a softer look */
        color: #f5f5f5; /* Light text color for readability */
        margin-top: 20px;
    }

    .episode-body {
        line-height: 1.6;
        font-size: 16px;
        font-family: Arial, sans-serif;
        max-width: 800px; /* Maximum width for better reading experience */
        margin: 0 auto; /* Center align the content */
        word-wrap: break-word; /* Ensure that long words break to the next line */
        padding: 15px;
        background-color: #333; /* Slightly lighter background for the body */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Adding subtle shadow */
    }

    /* Optional: Adding specific styles for links inside the body */
    .episode-body a {
        color: #ffcc00;
        text-decoration: underline;
    }

    .episode-body a:hover {
        color: #ffaa00;
    }

    .episode-body img {
        max-width: 100%;
        height: auto;
        border-radius: 4px; /* Optional: for rounded corners on images */
        margin-bottom: 15px; /* Space between images and text */
    }

    .episode-body video {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    /***************************************/

    /* Shared styles for all icons */
    .home-icon, .previous-icon, .next-icon {
        transition: transform 0.3s ease, color 0.3s ease;
    }

    /* Hover effect for previous icon */
    .previous-icon:hover {
        transform: scale(1.2); /* Scale the icon */
        color: #ffcc00; /* Change color to yellow */
        animation: bounce 0.6s ease; /* Bounce animation */
    }

    /* Hover effect for next icon */
    .next-icon:hover {
        transform: scale(1.2); /* Scale the icon */
        color: #ffcc00; /* Change color to yellow */
        animation: bounce 0.6s ease; /* Bounce animation */
    }

    /* Hover effect for home icon */
    .home-icon:hover {
        transform: scale(1.2); /* Scale the icon */
        color: #ffcc00; /* Change color to yellow */
        animation: bounce 0.6s ease; /* Bounce animation */
    }

    /* Bounce animation */
    @keyframes bounce {
        0% {
            transform: scale(1);
        }
        30% {
            transform: scale(1.1);
        }
        50% {
            transform: scale(1);
        }
        70% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    .thumbnail-container {
        position: relative;
        overflow: hidden;
    }

    .preview-video {
        z-index: 2;
    }

    .video-time-tracker {
        min-width: 120px;
        text-align: center;
        font-family: monospace;
    }

    #bookmarkList li {
        margin-bottom: 5px;
        cursor: pointer;
        transition: color 0.2s;
    }

    #bookmarkList li:hover {
        color: #0dcaf0; /* Bootstrap info color */
        text-decoration: underline;
    }

    .transcript-item {
        padding: 5px 10px;
        border-left: 4px solid transparent;
        transition: background-color 0.2s, border-color 0.2s;
    }

    .transcript-item:hover {
        background-color: #333;
        cursor: pointer;
    }

    .active-transcript {
        background-color: #0dcaf0;
        color: black;
        border-left-color: #0d6efd;
        font-weight: bold;
    }

    .accordion-toggle {
        width: 100%;
        text-align: left;
        background-color: #222;
        color: #fff;
        padding: 10px 15px;
        font-size: 1.1rem;
        font-weight: bold;
        border: none;
        outline: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
        border-radius: 5px;
        margin-bottom: 5px;
    }

    .accordion-toggle:hover {
        background-color: #333;
    }

    .accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease-out, padding 0.3s ease;
        background-color: #1b1b1b;
        border-radius: 5px;
        padding: 0 15px;
    }

    .accordion-section.open .accordion-content {
        padding: 15px;
        max-height: 500px; /* Adjust as needed */
    }

    .video-js {
        border: 3px solid #28A745;
        border-radius: 10px;
    }

    .vjs-play-control {
        background-color: #28a745;
    }

    .badge.hashtag-link {
        color: #fff !important;
    }

    .badge.hashtag-link:hover {
        color: #000 !important;
        background-color: #5a6268 !important; /* optional darker hover */
        text-decoration: none;
    }

    @media (max-width: 576px) {
        .dropdown-menu {
            width: 100% !important;
            left: 0 !important;
            right: 0 !important;
        }
    }

    .glow-border-box {
        position: relative;
        padding: 1rem;
        border-radius: 16px;
        background: #111;
        max-height: 500px;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1;

        scrollbar-width: thin;
        scrollbar-color: #ff4d4d #1a1a1a;
    }

    .glow-border-box::-webkit-scrollbar {
        width: 6px;
    }

    .glow-border-box::-webkit-scrollbar-track {
        background: #1a1a1a;
        border-radius: 10px;
    }

    .glow-border-box::-webkit-scrollbar-thumb {
        background: #ff4d4d;
        border-radius: 10px;
    }

    .glow-border-box::before {
        content: '';
        position: absolute;
        top: -1px;
        left: -1px;
        right: -1px;
        bottom: -1px;
        border-radius: 18px;
        background: linear-gradient(90deg, red, orange, yellow, green, cyan, blue, violet, red);
        background-size: 300% 300%;
        z-index: -1;
        animation: borderPulse 6s linear infinite;
        pointer-events: none;
        mask:
                linear-gradient(#000 0 0) content-box,
                linear-gradient(#000 0 0);
        mask-composite: exclude;
        -webkit-mask:
                linear-gradient(#000 0 0) content-box,
                linear-gradient(#000 0 0);
        -webkit-mask-composite: destination-out;
    }

    @keyframes borderPulse {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .clearfix::after {
        content: "";
        display: table;
        clear: both;
    }
</style>
@endsection

@php
$play_next = $play->episode_number + 1;
$comingUp = \App\Models\Episode::where('course_id',$course->id)->where('episode_number',$play_next)->first();
if($play->episode_number >= 2){
$play_prev_option = true;
$play_prev = $play->episode_number - 1;
$lastOne = \App\Models\Episode::where('course_id',$course->id)->where('episode_number',$play_prev)->first();
$lastOne = $lastOne->slug;
}else{
$play_prev_option = false;
}

if($comingUp){
$nextSlug = $comingUp->slug;
}else{
$nextSlug = null;
}
@endphp

@section('wrapper')
<div class="main px-lg-4 px-md-4 bg-dark-defualt">
    <div class="header">
        @include('courses.player.layouts.navbar')
    </div>

    <div class="body d-flex py-lg-3 py-md-2">
        <div class="container-fluid">
            {{--                @include('courses.player.layouts.related_courses')--}}

            <div class="row clearfix">
                @include('courses.player.layouts.play_list',['play' => $play])

                <div>
                    <div class="text-center mt-2">
                        <button onclick="changeSpeed(0.75)" class="btn btn-sm btn-secondary">0.75x</button>
                        <button onclick="changeSpeed(1)" class="btn btn-sm btn-primary">1x</button>
                        <button onclick="changeSpeed(1.25)" class="btn btn-sm btn-warning">1.25x</button>
                        <button onclick="changeSpeed(1.5)" class="btn btn-sm btn-danger">1.5x</button>
                    </div>
                    <br>
                    <div class="flex-grow-1" style="height: 8px; background-color: #444; border-radius: 5px;">
                        <div id="custom-progress" style="height: 100%; width: 0%; background-color: #28a745; border-radius: 5px;"></div>
                    </div>
                </div>

                @include('courses.player.layouts.video_box',['play' => $play])

                <div class="text-center mt-3">
                    <button onclick="markCurrentTime()" class="btn btn-outline-info btn-sm">
                        📌 Mark This Moment
                    </button>
                </div>

                <!-- Bookmarked Moments Accordion -->
                <div class="accordion-section mt-3 text-white">
                    <button class="accordion-toggle">📍 Bookmarked Moments</button>
                    <div class="accordion-content">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="languageSelect" class="mb-0 d-flex align-items-center">
                                <i class="bi bi-person-circle me-2"></i> <!-- Icon for User -->
                                User Menu Options:
                            </label>

                            <div class="dropdown">
                                <button class="btn btn-dark dropdown-toggle" type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person me-2"></i> <!-- User Icon -->
                                    @if(auth()->check())
                                    Save your moments
                                    @else
                                    Not logged in
                                    @endif
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="userMenuDropdown">
                                    @if(auth()->check())
                                    <li><a class="dropdown-item" href="#">Save moments</a></li>
                                    <li><a class="dropdown-item" href="#">Clear moments</a></li>
                                    @else
                                    <li><a class="dropdown-item" href="/register">Login</a></li>
                                    <li><a class="dropdown-item" href="/login">Register</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <!-- Bookmark list with placeholder for empty state -->
                        <ul id="bookmarkList" class="list-unstyled mb-0">
                            <!-- Placeholder text if no bookmarks -->
                            <li id="emptyBookmarkMessage" class="text-muted" style="display: none;">
                                No bookmarks added yet.
                            </li>
                        </ul>
                    </div>
                </div>
                @if(request()->ip() === "178.131.21.62")
                <!-- Transcript / Highlights Accordion -->
                <div class="accordion-section mt-4 text-white">
                    <button class="accordion-toggle">📝 Transcript / Highlights</button>
                    <div class="accordion-content">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="languageSelect" class="mb-0 d-flex align-items-center">
                                <i class="bi bi-globe me-2"></i> <!-- Globe Icon -->
                                Language:
                            </label>

                            <div class="dropdown">
                                <button class="btn btn-dark dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-globe me-2"></i> <!-- Globe Icon -->
                                    @if(session('locale'))
                                    {{ strtoupper(session('locale')) }} <!-- Display the current language code -->
                                    @else
                                    EN <!-- Default language code (can be adjusted) -->
                                    @endif
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                                    @foreach(\App\Models\Language::where('is_active', 1)->get() as $language)
                                    <li>
                                        <a class="dropdown-item" href="">
                                            {{ $language->name }} <!-- Display the language name -->
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>


                        <ul id="transcript-list" class="list-unstyled mb-0"></ul>
                    </div>
                </div>
                @endif

                @include('courses.player.layouts.description_body', ['play' => $play])
            </div>

            <div class="row clearfix">
                @include('courses.player.layouts.footer')
            </div>

        </div>
        <!--            <div class="row clearfix">-->
        <!--                @include('courses.player.layouts.comment_box')-->
        <!--            </div>-->
    </div>
</div>
@endsection

@section('scripts')
<script>
    const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
</script>
<script>
    const transcriptOverlay = document.getElementById('transcriptOverlay');
    const video = document.getElementById('my-video');
    const nextSlug = {!! json_encode($nextSlug) !!};

    video.addEventListener('ended', function () {
        console.log('Video ended');

        if (!isLoggedIn) {
            console.log('User is not logged in');
            Swal.fire({
                icon: 'info',
                title: 'Login Required',
                text: 'You must be logged in to earn reputation points!',
                confirmButtonText: 'Login Now',
                showCancelButton: true,
                cancelButtonText: 'Maybe Later',
                preConfirm: () => {
                    window.location.href = '/login';
                }
            });
            return;  // Exit early if not logged in
        }

        console.log('User is logged in. Proceeding with reputation reward...');

        // If logged in, proceed to reward
        fetch('/give-reputation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                episode_id: {{ $play->id }}
    })
    })
    .then(res => res.json())
            .then(data => {
                console.log('Reputation response data:', data);

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '🎉 Reputation Earned!',
                        text: '+10 points for completing the episode!',
                        showCancelButton: true,
                        cancelButtonText: 'Maybe Later',
                        confirmButtonText: 'Go to Next Episode',
                        preConfirm: () => {
                            if (nextSlug) {
                                window.location.href = `{{ route('courses.show.rest', ['slug' => $course->slug, 'epi' => $nextSlug]) }}`;
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Already Rewarded',
                        text: data.message || 'You already earned reputation for this episode.',
                        showCancelButton: true,
                        cancelButtonText: 'Maybe Later',
                        confirmButtonText: 'Go to Next Episode',
                        preConfirm: () => {
                            if (nextSlug) {
                                window.location.href = `{{ route('courses.show.rest', ['slug' => $course->slug, 'epi' => $nextSlug]) }}`;
                            }
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error during fetch request:', error);
            });
    });
</script>
<script>
    const currentTimeDisplay = document.getElementById('current-time');
    const totalTimeDisplay = document.getElementById('total-time');
    const playFromStartButton = document.getElementById('play-from-start');

    const pipButton = document.getElementById('pip');
    pipButton.addEventListener('click', function() {
        if (document.pictureInPictureEnabled) {
            video.requestPictureInPicture();
        }
    });

    const volumeControl = document.getElementById('volume');
    const muteButton = document.getElementById('mute-btn');

    volumeControl.addEventListener('input', function() {
        video.volume = volumeControl.value;
    });

    muteButton.addEventListener('click', function() {
        video.muted = !video.muted;
        muteButton.textContent = video.muted ? "🔊 Unmute" : "🔇 Mute";
    });

    // Update time tracker as video plays
    video.addEventListener('timeupdate', function () {
        let currentTime = video.currentTime;
        let duration = video.duration;

        // Format time to MM:SS
        let formatTime = (time) => {
            let minutes = Math.floor(time / 60);
            let seconds = Math.floor(time % 60);
            return `${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
        };

        // Update displayed time
        currentTimeDisplay.textContent = formatTime(currentTime);
        totalTimeDisplay.textContent = formatTime(duration);
    });

    // Play video from the beginning when button is clicked
    playFromStartButton.addEventListener('click', function () {
        video.currentTime = 0;
        video.play(); // Start the video from the beginning
    });
</script>
<script>
    const playPauseButton = document.getElementById('play-pause');
    const muteUnmuteButton = document.getElementById('mute-unmute');
    const fullscreenButton = document.getElementById('fullscreen');
    const skipForwardButton = document.getElementById('skip-forward');
    const skipBackwardButton = document.getElementById('skip-backward');

    // Play/Pause Toggle
    playPauseButton.addEventListener('click', function () {
        if (video.paused) {
            video.play();
            playPauseButton.textContent = "⏸️ Pause";
        } else {
            video.pause();
            playPauseButton.textContent = "▶️ Play";
        }
    });

    // Mute/Unmute Toggle
    muteUnmuteButton.addEventListener('click', function () {
        if (video.muted) {
            video.muted = false;
            muteUnmuteButton.textContent = "🔊 Mute";
        } else {
            video.muted = true;
            muteUnmuteButton.textContent = "🔇 Unmute";
        }
    });

    // Full Screen Toggle
    fullscreenButton.addEventListener('click', function () {
        if (video.requestFullscreen) {
            video.requestFullscreen();
        } else if (video.webkitRequestFullscreen) { // Safari
            video.webkitRequestFullscreen();
        } else if (video.msRequestFullscreen) { // IE/Edge
            video.msRequestFullscreen();
        }
    });

    // Skip Forward 10 Seconds
    skipForwardButton.addEventListener('click', function () {
        video.currentTime += 10;
    });

    // Skip Backward 10 Seconds
    skipBackwardButton.addEventListener('click', function () {
        video.currentTime -= 10;
    });
</script>
<script>

    // Check if video is loaded and playing
    video.addEventListener('play', function() {
        console.log("Video is playing.");
    });

    video.addEventListener('canplay', function() {
        console.log("Video can be played.");
    });
</script>
<script>
    const transcriptData = [
        { time: 9, text: "In the name of one above all, and with genuine regards and greetings to you who may be watching." },
        { time: 14, text: "My name is Sepehr Dehghan, undergraduate student of nanotechnology, and today" },
        { time: 19, text: "We're going to talk about what nanotechnology is." },
        { time: 22, text: "What does the word nanoscale mean, why is it so much important, and so many other questions?" },
        { time: 28, text: "So without further ado, let's dive into it, shall we?" },
        { time: 31, text: "What does the word nano mean, what does it imply?" },
        { time: 35, text: "Why is it so much important?" },
        { time: 37, text: "Let's find out." }
    ];
</script>
<script>
    // Handle accordion toggle
    document.querySelectorAll('.accordion-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const section = button.parentElement;
            section.classList.toggle('open');
        });
    });

    const bookmarks = [];
    const bookmarkList = document.getElementById('bookmarkList');
    const accordionSection = document.querySelector('.accordion-section');

    // Mark current time as a bookmark
    function markCurrentTime() {
        const time = video.currentTime;
        const label = `⏱ ${formatTime(time)}`;
        bookmarks.push(time);

        // Create the bookmark list item
        const li = document.createElement('li');
        li.textContent = label;
        li.onclick = () => {
            video.currentTime = time;
            video.play();
        };

        // Append the bookmark to the list
        bookmarkList.appendChild(li);

        // Open the accordion when a bookmark is added
        if (!accordionSection.classList.contains('open')) {
            accordionSection.classList.add('open');
        }
    }
</script>
<script>
    // Listen for keydown events
    document.addEventListener('keydown', function(event) {
        // Prevent default actions for specific keys like spacebar in some cases
        if (event.target === document.body) {
            if (event.key === ' ' || event.key === 'Spacebar') {
                // Toggle play/pause on Spacebar
                event.preventDefault(); // Prevent scrolling down the page with spacebar
                togglePlayPause();
            } else if (event.key === 'ArrowRight') {
                // Skip 5 seconds forward
                skipForward();
            } else if (event.key === 'ArrowLeft') {
                // Skip 5 seconds backward
                skipBackward();
            } else if (event.key === 'ArrowUp') {
                // Increase volume by 10%
                adjustVolume(0.1);
            } else if (event.key === 'ArrowDown') {
                // Decrease volume by 10%
                adjustVolume(-0.1);
            } else if (event.key === 'm' || event.key === 'M') {
                // Mute/unmute the video
                toggleMute();
            } else if (event.key === 'f' || event.key === 'F') {
                // Toggle fullscreen
                toggleFullscreen();
            }
        }
    });

    // Play/Pause function
    function togglePlayPause() {
        if (video.paused) {
            video.play();
        } else {
            video.pause();
        }
    }

    // Skip forward by 5 seconds
    function skipForward() {
        video.currentTime += 5;
    }

    // Skip backward by 5 seconds
    function skipBackward() {
        video.currentTime -= 5;
    }

    // Adjust volume by a given amount
    function adjustVolume(amount) {
        let newVolume = video.volume + amount;
        if (newVolume > 1) newVolume = 1;
        if (newVolume < 0) newVolume = 0;
        video.volume = newVolume;
    }

    // Toggle mute/unmute
    function toggleMute() {
        video.muted = !video.muted;
    }

    // Toggle fullscreen mode
    function toggleFullscreen() {
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else {
            video.requestFullscreen();
        }
    }
</script>
<script>
    const transcriptList = document.getElementById("transcript-list");

    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }

    transcriptData.forEach((entry, index) => {
        const li = document.createElement("li");
        li.textContent = `⏱ ${formatTime(entry.time)} – ${entry.text}`;
        li.setAttribute("data-time", entry.time);
        li.classList.add("transcript-item");
        li.onclick = () => {
            video.currentTime = entry.time;
            video.play();
        };
        transcriptList.appendChild(li);
    });
</script>
<script>
    function highlightTranscript(currentTime) {
        document.querySelectorAll('.transcript-item').forEach(item => {
            const time = parseFloat(item.getAttribute('data-time'));
            const nextItem = item.nextElementSibling;
            const nextTime = nextItem ? parseFloat(nextItem.getAttribute('data-time')) : Infinity;

            if (currentTime >= time && currentTime < nextTime) {
                item.classList.add('active-transcript');
            } else {
                item.classList.remove('active-transcript');
            }
        });
    }

    setInterval(() => {
        if (!video.paused && !video.ended) {
            highlightTranscript(video.currentTime);
        }
    }, 500);
</script>
<script>
    function changeSpeed(rate) {
        video.playbackRate = rate;
    }

    window.addEventListener('beforeunload', () => {
        localStorage.setItem(`video-${video.src}`, video.currentTime);
    });

    window.addEventListener('load', () => {
        const savedTime = localStorage.getItem(`video-${video.src}`);
        if (savedTime) {
            video.currentTime = parseFloat(savedTime);
        }
    });
</script>
<script>
    const timeTracker = document.getElementById('timeTracker');
    const customProgress = document.getElementById('custom-progress');

    function formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60);
        return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }

    function updateTime() {
        const current = formatTime(video.currentTime);
        const total = formatTime(video.duration || 0);
        const percent = (video.currentTime / video.duration) * 100 || 0;

        timeTracker.textContent = `⏱ ${current} / ${total}`;
        customProgress.style.width = `${percent}%`;
    }

    function changeSpeed(rate) {
        video.playbackRate = rate;
    }

    video.addEventListener('timeupdate', updateTime);
    video.addEventListener('loadedmetadata', updateTime);
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const video = document.getElementById('my-video');
        const timeTracker = document.getElementById('timeTracker');

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
            const secs = Math.floor(seconds % 60).toString().padStart(2, '0');
            return `${mins}:${secs}`;
        }

        video.addEventListener('loadedmetadata', () => {
            timeTracker.textContent = `⏱ 00:00 / ${formatTime(video.duration)}`;
        });

        video.addEventListener('timeupdate', () => {
            const current = formatTime(video.currentTime);
            const total = formatTime(video.duration);
            const remaining = formatTime(video.duration - video.currentTime);
            const isPaused = video.paused ? '⏸ Paused' : '▶️ Playing';
            const speed = video.playbackRate.toFixed(1);

            timeTracker.innerHTML = `
        ⏱ ${current} / ${total}
        <br>
        ⏳ Remaining: ${remaining} | ⚡ Speed: ${speed}x | ${isPaused}
    `;
        });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('success') }}",
        showConfirmButton: true,
    });
</script>
@endif

@if(session('info'))
<script>
    Swal.fire({
        title: 'Info',
        text: "{{ session('info') }}",
        icon: 'info',
        confirmButtonText: 'Okay'
    });
</script>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const activeEpisode = document.getElementById("active-episode");
        const playlist = document.getElementById("episode-playlist");

        if (activeEpisode && playlist) {
            const offsetTop = activeEpisode.offsetTop - playlist.offsetTop;
            playlist.scrollTop = offsetTop - 20; // Smooth center-ish scroll
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.thumbnail-container').forEach(container => {
            const video = container.querySelector('.preview-video');

            if (!video) return;

            container.addEventListener('mouseenter', () => {
                video.style.display = 'block';
                video.currentTime = 0;
                video.play();
            });

            container.addEventListener('mouseleave', () => {
                video.pause();
                video.style.display = 'none';
            });
        });
    });
</script>
<script>
    window.addEventListener('beforeunload', () => {
        localStorage.setItem(`video-${video.src}`, video.currentTime);
    });

    window.addEventListener('load', () => {
        const savedTime = localStorage.getItem(`video-${video.src}`);
        if (savedTime) {
            video.currentTime = parseFloat(savedTime);
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const playPauseBtn = document.getElementById('playPauseBtn');
        const playIcon = document.getElementById('playIcon');
        const pauseIcon = document.getElementById('pauseIcon');

        if (playPauseBtn && playIcon && pauseIcon) {
            playPauseBtn.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default anchor behavior

                if (video.paused) {
                    video.play();
                    playIcon.style.display = 'none';
                    pauseIcon.style.display = 'inline'; // Show pause icon
                } else {
                    video.pause();
                    playIcon.style.display = 'inline'; // Show play icon
                    pauseIcon.style.display = 'none';
                }
            });
        } else {
            console.error('One or more elements not found: playPauseBtn, playIcon, pauseIcon');
        }

        const volumeControl = document.getElementById('volumeControl');

        if (volumeControl) {
            // Set initial volume from slider value
            video.volume = volumeControl.value / 100;

            // Volume control slider input handler
            volumeControl.addEventListener('input', function() {
                video.volume = volumeControl.value / 100;
            });
        } else {
            console.error('Volume control element not found');
        }
    });

</script>
@endsection