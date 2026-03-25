<style>
    .youtube-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #ff0000, #cc0000);
        color: white;
        border-radius: 40px;
        font-weight: bold;
        font-size: 16px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(255, 0, 0, 0.4);
        transition: all 0.4s ease;
        animation: float 2s ease-in-out infinite alternate;
        position: relative;
        overflow: hidden;
    }

    .youtube-link i {
        font-size: 20px;
        animation: pulse 2s infinite;
    }

    .youtube-link:hover {
        background: linear-gradient(135deg, #cc0000, #990000);
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(255, 0, 0, 0.6);
    }

    .youtube-link span {
        transition: transform 0.3s ease;
    }

    .youtube-link:hover span {
        transform: translateX(5px);
    }

    /* Floating animation */
    @keyframes float {
        0%   { transform: translateY(0); }
        100% { transform: translateY(-5px); }
    }

    /* Pulse animation for icon */
    @keyframes pulse {
        0%   { transform: scale(1); }
        50%  { transform: scale(1.15); }
        100% { transform: scale(1); }
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


</style>

<div class="col-xxl-3 col-xl-3 col-lg-4 col-md-12 order-2">
    <div class="card border-0 my-4 bg-dark ">
        <div id="rightchatbox" class="rightchatbox p-3">
            <div id="friendslist">

                <div class="glow-border-box">
                    <div id="friends">
                        @if($play)
                            <div>
                                <span title="Episode Number">
                                    <strong>{{ $play->episode_number }}-</strong>
                                </span>
                                {{ $play->title }}
                            </div>
                            <hr>
                            <div>
                                {!! $play->body ?? $play->description !!}
                            </div>

                            @if($play->episode_iframe)
                                <div class="watch-on-youtube mt-3">
                                    <a href="{{ $play->episode_iframe }}" target="_blank" class="youtube-link">
                                        <i class="fab fa-youtube"></i>
                                        <span>Watch on YouTube</span>
                                    </a>
                                </div>
                            @endif

                            <style>
                                /* Download button styling */
                                .download-video .download-link {
                                    display: inline-flex;
                                    align-items: center;
                                    padding: 10px 20px;
                                    background-color: #4CAF50; /* Green color for download */
                                    color: white;
                                    text-decoration: none;
                                    border-radius: 5px;
                                    font-weight: bold;
                                    transition: all 0.3s ease;
                                }

                                .download-video .download-link:hover {
                                    background-color: #3e8e41;
                                    transform: translateY(-2px);
                                }

                                .download-video .download-link i {
                                    margin-right: 8px;
                                    font-size: 1.2em;
                                }

                                /* Existing YouTube button styling */
                                .watch-on-youtube .youtube-link {
                                    display: inline-flex;
                                    align-items: center;
                                    padding: 10px 20px;
                                    background-color: #FF0000; /* YouTube red */
                                    color: white;
                                    text-decoration: none;
                                    border-radius: 5px;
                                    font-weight: bold;
                                    transition: all 0.3s ease;
                                }

                                .watch-on-youtube .youtube-link:hover {
                                    background-color: #CC0000;
                                    transform: translateY(-2px);
                                }

                                .watch-on-youtube .youtube-link i {
                                    margin-right: 8px;
                                    font-size: 1.2em;
                                }
                            </style>

                            <!-- show episode last updated at time date nicely  -->
                            @if($play->updated_at)
                                <hr class="sep-1">
                                {{--                            <div style="font-size: 0.9rem; color: #888; font-style: italic;">--}}
                                {{--                                Date created: {{ \Carbon\Carbon::parse($play->created_at)->format('F j, Y, g:i a') }}--}}
                                {{--                            </div>--}}
                                {{--                            <div style="font-size: 0.9rem; color: #888; font-style: italic;">--}}
                                {{--                                Last updated: {{ \Carbon\Carbon::parse($play->updated_at)->format('F j, Y, g:i a') }}--}}
                                {{--                            </div>--}}
                                {{--                            <div style="font-size: 1rem; color: #fff; font-weight: bold; margin-top: 10px;">--}}
                                {{--                                <span style="color: #007bff;">Views:</span> <span style="color: #fff;">{{ $play->view_count }}</span>--}}
                                {{--                            </div>--}}
                                @php
                                    $excludedWords = ['in', 'the', 'and', 'of', 'a', 'from', 'to', 'different', 'at', 'off', 'on'];
                                    $courseWords = $course->title ? explode(' ', $course->title) : [];
                                    $playWords = $play->title ? explode(' ', $play->title) : [];
                                    $allWords = array_unique(array_merge($courseWords, $playWords));
                                @endphp

                                @if(!empty($allWords))
                                    <div class="mt-3 text-center">
                                        @foreach($allWords as $word)
                                            @php
                                                $tag = preg_replace('/[^\p{L}\p{N}_]+/u', '', $word); // clean word
                                            @endphp

                                            @if($tag && !in_array(strtolower($tag), $excludedWords))
                                                <a title="Search for {{ strtoupper($tag) }} keyword"
                                                   class="badge bg-secondary hashtag-link mx-1">
                                                    #{{ strtolower($tag) }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{--                                <div class="teacher-profile mt-4 p-3 rounded bg-gradient-dark text-white">--}}
                                {{--                                    <div class="d-flex align-items-center gap-3">--}}
                                {{--                                        <img src="/images/teacher-profile.jpg" alt="Teacher Photo" class="rounded-circle" width="70" height="70" style="object-fit: cover; border: 2px solid #ff5555;">--}}
                                {{--                                        <div>--}}
                                {{--                                            <h5 class="mb-1" style="font-weight: bold;">Dr. Sara Khadem</h5>--}}
                                {{--                                            <p class="mb-0" style="font-size: 0.9rem; color: #ccc;">--}}
                                {{--                                                Nanotechnology Expert & Educator--}}
                                {{--                                            </p>--}}
                                {{--                                            <p class="mt-2" style="font-size: 0.85rem; color: #aaa;">--}}
                                {{--                                                Passionate about bridging science and society. Dr. Khadem has over 12 years of experience in research and public education in advanced materials.--}}
                                {{--                                            </p>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}

                                <style>
                                    .bg-gradient-dark {
                                        background: linear-gradient(135deg, #1c1c1c, #2c2c2c);
                                    }

                                    .teacher-profile:hover {
                                        background: linear-gradient(135deg, #2c2c2c, #3a3a3a);
                                        transform: translateY(-3px);
                                        transition: all 0.3s ease-in-out;
                                        box-shadow: 0 4px 12px rgba(255, 0, 0, 0.3);
                                    }
                                </style>


                                <div class="text-center my-4">
                                    <a href="{{ $play->video_path }}"
                                       download
                                       class="btn btn-lg btn-primary shadow-lg px-5 py-3"
                                       style="font-size: 1.2rem; font-weight: bold; border-radius: 50px; background: linear-gradient(to right, #6a11cb, #2575fc); border: none;">
                                        <i class="fas fa-download mr-2"></i> Download Video
                                    </a>
                                </div>



                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>