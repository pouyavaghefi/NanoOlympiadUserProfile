<div class="col-xxl-2 col-xl-2 col-lg-12 col-md-12 order-2 order-sm-2 order-md-2 order-lg-1">
    <div class="sidebar mx-0 video-menu px-4 py-4 order-1">
        <div class="d-flex flex-xl-column flex-xxl-column h-100">
            <a href="" class="mb-0 brand-icon">
                <span class="logo-icon episode-icon" title="View Episodes">
                    <i class="fas fa-clapperboard"></i>
                </span>
                <span class="logo-text">Episodes</span>
            </a>

            <!-- Menu: main ul - Modified for horizontal scrolling on mobile -->
            <div class="menu-container glow-border-box" id="episode-playlist" style="max-height: 80vh; overflow-y: auto;">
                <ul class="menu-list flex-grow-1 mt-3">
                    @forelse($episodes as $episode)
                        @php
                            $isFirstEpisode = $episode->episode_number < 2;
                            $episodeRouteToPlay = $isFirstEpisode
                                ? route('courses.show', ['slug' => $course->slug])
                                : route('courses.show.rest', ['slug' => $course->slug, 'epi' => $episode->slug]);

                            $currentUrl = request()->url();
                            $isCurrent = $currentUrl === $episodeRouteToPlay;

                            $previewPath = $episode->video_path ?? null;
                        @endphp

                        <li class="episode-item {{ $isCurrent ? 'current-episode' : '' }}" id="{{ $isCurrent ? 'active-episode' : '' }}">
                            <a class="m-link flex-column" href="{{ $episodeRouteToPlay }}">
                                <div class="thumbnail-container position-relative">
                                    <img class="img-thumbnail img-fluid"
                                         style="width: 150px; height: 100px; object-fit: cover; border-radius: 6px;"
                                         @if(is_null($episode->thumb_path)) src="/course_player/img/chapter.png"
                                         @else src="{{ $episode->thumb_path }}"
                                         @endif
                                         alt="{{ $episode->title }}"
                                         loading="lazy">

                                    @if($previewPath)
                                        <video class="preview-video position-absolute top-0 start-0 w-100 h-100"
                                               muted
                                               preload="metadata"
                                               style="object-fit: cover; display: none; border-radius: 6px;"
                                               src="{{ $previewPath }}">
                                        </video>
                                    @endif

                                    @if($episode->time)
                                        <span class="episode-duration position-absolute bottom-0 end-0 m-1 px-2 py-1"
                                              style="font-size: 0.7rem; border-radius: 4px; z-index: 5; @if($isCurrent) background-color:white;color:black; @else color:white;background-color:black; @endif">
                                            {{ $episode->time }}
                                        </span>
                                    @endif

                                    @if($isCurrent)
                                        <div class="play-indicator">
                                            <span class="bi bi-play-fill"></span>
                                        </div>
                                    @endif
                                </div>
                                <span class="small-xs text-start w-100 mt-2 color-400 text-truncate d-flex align-items-center">
                                    <span class="badge rounded-pill bg-primary me-2 fw-normal">
                                        {{ $episode->episode_number }}
                                    </span>

                                    <span class="typewriter-container">
                                        <span class="{{ $isCurrent ? 'typewriter-text long-typing' : '' }}">
                                            {{ $episode->title }}
                                        </span>
                                    </span>
                                </span>
                            </a>
                        </li>
                    @empty
                        <li>No episodes available.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Menu: menu collepce btn -->
            <a href="/courses" title="Go Back to Home" class="btn btn-link sidebar-mini-btn text-light">
                <span class="ms-2">
                    <i class="icofont-bubble-right icon-effect"></i>
                </span>
            </a>
        </div>
    </div>
</div>

<style>
    .episode-icon {
        transition: transform 0.5s ease, color 0.5s ease;
    }

    /* Pulsating effect on the current episode icon */
    .episode-item.current-episode .episode-icon {
        color: #0d6efd; /* Blue color for the current episode */
        animation: pulse 1.5s infinite; /* Apply the pulsating effect */
    }

    /* Animation for pulsating effect */
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .icon-effect {
        font-size: 24px;
        color: #ff4d4d; /* Red color by default */
        transition: all 0.3s ease;
        position: relative;
    }

    .icon-effect:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 50%;
        background: rgba(255, 77, 77, 0.3); /* Slight red glow */
        opacity: 1; /* Always visible glow */
        transition: opacity 0.3s ease;
    }

    /* Optional: Glow intensifies on hover */
    .icon-effect:hover {
        transform: scale(1.1);
        color: #ff0000; /* Slightly more intense red on hover */
    }

    .icon-effect:hover:before {
        opacity: 0.4; /* Glow becomes slightly more intense on hover */
    }

    .episode-duration {
        font-size: 0.7rem;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 4px;
        z-index: 5;
    }

    .thumbnail-container {
        font-size: 0.7rem;
        background: rgba(0, 0, 0, 0.6);
        border-radius: 4px;
        padding: 2px 6px;
    }
    .typewriter-container {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .typewriter-text {
        display: inline-block;
        white-space: nowrap;
        position: relative;
        padding-right: 1em; /* buffer for cursor */
        animation: typing-scroll 10s linear infinite; /* Infinite loop */
    }

    /* Blinking cursor */
    .typewriter-text::after {
        content: "";
        display: inline-block;
        width: 2px;
        height: 1em;
        background-color: black;
        margin-left: 2px;
        animation: blink 0.7s steps(1) infinite;
        vertical-align: bottom;
    }

    /* The scroll typewriter effect */
    @keyframes typing-scroll {
        0% {
            transform: translateX(100%);
        }
        50% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-100%);
        }
    }

    /* Cursor blink animation */
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }

    .episode-item.current-episode {
        background: linear-gradient(to right, #f0f8ff, #e6f2ff);
        border-left: 4px solid #0d6efd;
        border-radius: 8px;
        padding-left: 8px;
        transition: background 0.3s;
    }

    .episode-item.current-episode .m-link {
        font-weight: 500;
        color: #0d6efd;
    }

    .play-indicator {
        position: absolute;
        top: 8px;
        right: 8px;
        background-color: rgba(13, 110, 253, 0.85);
        color: white;
        border-radius: 50%;
        padding: 4px;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .menu-container {
        scrollbar-width: thin;
        scrollbar-color: #c2c2c2 transparent;
    }

    .menu-container::-webkit-scrollbar {
        width: 6px;
    }

    .menu-container::-webkit-scrollbar-thumb {
        background-color: #c2c2c2;
        border-radius: 3px;
    }

    .episode-item.current-episode .m-link span {
        color: #000 !important; /* Title becomes black for active episode */
    }

    .episode-item.current-episode .badge {
        background-color: #0d6efd;
        color: #fff;
    }

    .episode-item.current-episode .m-link span {
        color: #000 !important; /* Affects entire span */
    }

    .episode-item.current-episode .badge {
        background-color: #0d6efd !important;
        color: #fff !important;
    }

    .current-episode .scrolling-title {
        max-height: 20px;
        overflow: hidden;
        position: relative;
    }

    .current-episode .scrolling-title span {
        display: inline-block;
        white-space: nowrap;
        animation: scrollTitleUp 5s linear infinite;
        padding-left: 2px;
    }

    @keyframes scrollTitleUp {
        0%   { transform: translateY(0%); }
        50%  { transform: translateY(-100%); }
        100% { transform: translateY(0%); }
    }

    .episode-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        margin-right: 0.5rem;
        background-color: #0d6efd;
        color: white;
        font-weight: 500;
    }

    /* Desktop layout - vertical scroll */
    .menu-container {
        width: 100%;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .menu-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding: 0 10px;
    }

    .episode-item {
        width: 100%;
    }

    /* Mobile layout - horizontal scroll */
    @media (max-width: 768px) {
        .menu-container {
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
            scrollbar-width: none; /* Hide scrollbar on Firefox */
        }

        .menu-container::-webkit-scrollbar {
            display: none; /* Hide scrollbar on Chrome/Safari */
        }

        .menu-list {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            padding-bottom: 15px; /* Space for scroll */
        }

        .episode-item {
            flex: 0 0 auto;
            width: 150px;
            margin-right: 15px;
        }

        .thumbnail-container {
            width: 150px;
            height: 100px;
        }
    }

    /* Ensure text doesn't overflow */
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
</style>
