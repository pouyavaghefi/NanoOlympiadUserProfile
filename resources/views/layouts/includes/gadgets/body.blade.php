<div class="body d-flex py-lg-3 py-md-2">
    <div class="container-xxl">
        @if(auth()->user()->member->passport_verified == 0)
            <div class="alert alert-warning d-flex align-items-center justify-content-between p-3 mb-4 shadow-sm border border-warning">
                <div>
                    <strong>⚠️ Action Required:</strong> Your passport photo is not verified.
                </div>
                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-warning">
                    Update Profile
                </a>
            </div>
        @endif

            @if (is_null(auth()->user()->avatar))
                <div class="alert alert-warning d-flex align-items-center justify-content-between p-3 mb-4 shadow-sm border border-warning">
                    <div>
                        <strong>⚠️ Action Required:</strong> Your avatar photo is not verified.
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-warning">
                        Update Profile
                    </a>
                </div>
            @endif

            <div class="row clearfix g-3">

            <!-- Left: Quote + Welcome -->
            <div class="col-lg-4 col-md-12">
                <div class="card mb-3 color-bg-200">
                    <div class="card-body">
                        <div class="text-center p-4">
                            <i class="icofont-quote-left fs-1 text-primary"></i>
                            <p class="fst-italic mt-3">"Education is the passport to the future, for tomorrow belongs to those who prepare for it today."</p>
                            <small class="text-muted">- Malcolm X</small>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-5 order-lg-2">
                                <div class="text-center p-4">
                                    <img src="assets/images/study.svg" alt="Study illustration" class="img-fluid set-md-img" />
                                </div>
                            </div>
                            <div class="col-12 col-lg-7 order-lg-1">
                                <h3 class="mb-3">Welcome back, <span class="fw-bold">{{ auth()->user()->fname }}</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle: Learning Progress -->
{{--            <div class="col-lg-4 col-md-12 d-flex align-items-center">--}}
{{--                <div class="card mb-3 color-bg-150 w-100 text-center p-4">--}}
{{--                    <h5 class="mb-3 fw-bold">Your Learning Progress</h5>--}}

{{--                    <div style="width: 140px; height: 140px; margin: 0 auto;">--}}
{{--                        <svg viewBox="0 0 36 36" class="circular-chart blue">--}}
{{--                            <path class="circle-bg"--}}
{{--                                  d="M18 2.0845--}}
{{--                       a 15.9155 15.9155 0 0 1 0 31.831--}}
{{--                       a 15.9155 15.9155 0 0 1 0 -31.831" />--}}
{{--                            <path class="circle"--}}
{{--                                  stroke-dasharray="{{ $userProgressPercentage ?? 0 }}, 100"--}}
{{--                                  d="M18 2.0845--}}
{{--                       a 15.9155 15.9155 0 0 1 0 31.831--}}
{{--                       a 15.9155 15.9155 0 0 1 0 -31.831" />--}}
{{--                            <text x="18" y="20.35" class="percentage" text-anchor="middle">{{ $userProgressPercentage ?? 0 }}%</text>--}}
{{--                        </svg>--}}
{{--                    </div>--}}

{{--                    <p class="mt-3 mb-2 fw-semibold">Course Completion</p>--}}

{{--                    <span class="badge bg-success mb-3" style="font-size: 1rem;">--}}
{{--            🔥 {{ $userStreakDays ?? 0 }} Day Learning Streak!--}}
{{--          </span>--}}

{{--                    <div class="d-flex flex-column gap-2">--}}
{{--                        <a href="https://ino-official.org/courses/course-player/international-nanotechnology-olympiad-academy"--}}
{{--                           class="btn btn-primary btn-sm w-100"--}}
{{--                           target="_blank" rel="noopener noreferrer"--}}
{{--                           aria-label="Nanotechnology Courses">--}}
{{--                            Nanotechnology Courses--}}
{{--                        </a>--}}
{{--                        <a href="https://ino-official.org/courses/book-lets"--}}
{{--                           class="btn btn-primary btn-sm w-100"--}}
{{--                           target="_blank" rel="noopener noreferrer"--}}
{{--                           aria-label="Booklets">--}}
{{--                            Booklets--}}
{{--                        </a>--}}
{{--                        <a href="https://ino-official.org/courses/sample-questions" target="_blank"--}}
{{--                           class="btn btn-primary btn-sm w-100"--}}
{{--                           rel="noopener noreferrer"--}}
{{--                           aria-label="Sample Questions">--}}
{{--                            Sample Questions--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <!-- Right: Digital Clock -->
            <div class="col-lg-4 col-md-12">
                <div class="card bg-dark mb-3">
                    <div class="card-body">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold text-white" id="current-date"></h6>
                        </div>
                        <div class="digital-clock d-flex justify-content-center align-items-center min-height-220">
                            <figure>
                                <div class="face top"><p id="s"></p></div>
                                <div class="face front"><p id="m"></p></div>
                                <div class="face left"><p id="h"></p></div>
                            </figure>
                        </div>
                        <script>
                            document.getElementById("current-date").textContent =
                                new Date().toLocaleDateString('en-US', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });
                        </script>

                    </div>
                </div>
            </div>
        </div>
        <!-- Dashboard Summary (full width) -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-3 color-bg-100">
                    <div class="card-header">
                        <h5>Your Dashboard Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4 border-end">
                                <h2 class="mb-0">{{ $userCoursesCount ?? 0 }}</h2>
                                <p class="mb-0">Courses Enrolled</p>
                            </div>
                            <div class="col-4 border-end">
                                <h2 class="mb-0">{{ $coursesNum ?? 0 }}</h2>
                                <p class="mb-0">Available Courses</p>
                            </div>
                            <div class="col-4">
                                <h2 class="mb-0">{{ $episodesNum ?? 0 }}</h2>
                                <p class="mb-0">Episodes Count</p>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-0">
                            Keep up the great work! Continue your learning journey and unlock new skills.
                        </p>
                    </div>
                </div>
            </div>
        </div> <!-- end dashboard summary row -->
    </div>
</div>

<style>
    .circular-chart {
        display: block;
        margin: 0 auto;
        max-width: 100%;
        max-height: 250px;
    }
    .circle-bg {
        fill: none;
        stroke: #eee;
        stroke-width: 3.8;
    }
    .circle {
        fill: none;
        stroke-width: 2.8;
        stroke-linecap: round;
        stroke: #007bff;
        transition: stroke-dasharray 0.6s ease 0s;
    }
    .percentage {
        font-size: 0.6em;
        fill: #333;
        dominant-baseline: middle;
    }
    .color-bg-150 {
        background-color: #e9f0ff;
    }
    .min-height-220 {
        min-height: 220px;
    }
</style>
