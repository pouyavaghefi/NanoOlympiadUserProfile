<nav class="navbar py-4">
    <div class="container-fluid">
        <style>
            .video-nav-group {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                align-items: center;
                justify-content: center;
                background: rgba(255, 255, 255, 0.05);
                padding: 0.7rem 1.2rem;
                border-radius: 12px;
                box-shadow: 0 0 12px rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(8px);
                animation: fadeInLinks 0.6s ease-out;
            }

            .video-nav-link {
                color: #fff;
                font-size: 1.8rem;
                transition: all 0.3s ease;
                border-radius: 50%;
                padding: 0.5rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .video-nav-link:hover {
                background-color: rgba(255, 255, 255, 0.1);
                transform: scale(1.2);
                box-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
            }

            .home-icon {
                font-size: 1.6rem;
                margin-left: 0.3rem;
            }

            .control-group {
                display: flex;
                align-items: center;
                gap: 1rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .control-group .video-nav-link {
                background-color: rgba(0, 0, 0, 0.1);
                border-radius: 50%;
                padding: 0.5rem;
            }

            .volume-slider {
                width: 100px;
            }

            .fullscreen-btn {
                font-size: 1.8rem;
            }

            .curved-underline {
                position: relative;
                display: inline-block;
            }

            .curved-underline::after {
                content: '';
                position: absolute;
                bottom: -5px;
                left: 0;
                width: 100%;
                height: 5px;
                background: linear-gradient(to right, transparent 0%, red 50%, transparent 100%);
                border-radius: 50%;
            }

            @keyframes fadeInLinks {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* Mobile-friendly styles */
            @media (max-width: 768px) {
                .video-nav-group {
                    justify-content: space-between;
                }

                .video-nav-link {
                    font-size: 1.5rem;
                    padding: 0.4rem;
                }

                .control-group {
                    flex-direction: column;
                    align-items: center;
                    gap: 1rem;
                }

                .volume-slider {
                    width: 80%;
                }
            }

            @media (max-width: 576px) {
                .video-nav-group {
                    flex-direction: column;
                    align-items: center;
                }

                .video-nav-link {
                    font-size: 1.4rem;
                    margin-bottom: 1rem;
                }

                .control-group {
                    flex-direction: column;
                    gap: 1rem;
                }

                .volume-slider {
                    width: 90%;
                }

                /* Add margin to title for spacing from the top */
                .navbar h3 {
                    margin-top: 1rem; /* Adjust this value to control the space */
                }
            }
        </style>

        <div class="video-nav-group">
            @if($play_prev_option && $lastOne)
                <a href=""
                   class="video-nav-link" title="Previous Episode">
                    <i class="icofont-arrow-left previous-icon"></i>
                </a>
            @endif

            <a href="{{ env('APP_URL') }}"
               class="video-nav-link" title="Go to Home">
                <i class="icofont-home home-icon"></i>
            </a>

            {{-- Booklets link --}}
                <a href="/courses"
                   class="video-nav-link" title="Courses">
                    <i class="icofont-book"></i>
                </a>

            @if($nextSlug)
                <a href="{{ route('courses.show.rest',['slug'=>$course->slug,'epi'=>$nextSlug]) }}"
                   class="video-nav-link" title="Next Episode">
                    <i class="icofont-arrow-right next-icon"></i>
                </a>
            @endif
        </div>

        <div class="control-group">
            <!-- Play/Pause Button -->
            <a href="#" class="video-nav-link" title="Play/Pause" id="playPauseBtn">
                <i class="icofont-play-alt-2 play-icon" id="playIcon"></i>
                <i class="icofont-pause pause-icon" id="pauseIcon" style="display: none;"></i>
            </a>

            <!-- Volume Control -->
            <input type="range" class="volume-slider" id="volumeControl" title="Adjust Volume" min="0" max="100" value="50" />

            <!-- Fullscreen Button -->
            <a href="#" class="video-nav-link fullscreen-btn" title="Fullscreen" id="fullscreenBtn">
                <i class="icofont-screen-full"></i>
            </a>
        </div>

        @include('courses.player.layouts.rightbar')

        <div class="order-0 col-lg-4 col-md-4 col-sm-12 col-12 mb-3 mb-md-0">
            <div class="input-group flex-nowrap input-group-lg">
                <a href="">
                    <h3 class="curved-underline" style="color:white">{{ $course->title }}</h3>
                </a>

                @if($course->subtitle)
                    <sup>{{ ucfirst($course->subtitle) }}</sup>
                @endif
            </div>
        </div>
    </div>
</nav>
