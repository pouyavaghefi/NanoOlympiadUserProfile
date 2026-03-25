<div class="body d-flex py-lg-3 py-md-2">
    <div class="container-xxl">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 mb-4 no-bg">
                    <div class="card-header py-3 px-0 d-flex align-items-center justify-content-between border-bottom">
                        <h3 class=" fw-bold flex-fill">All Courses</h3>
                        @include('layouts.includes.filters.course_filter')
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3 py-3">
            @foreach($courses as $course)
                <div class="col-lg-3 col-md-6 col-sm-6">
                    @include('layouts.includes.sections.all_courses', ['course' => $course])
                </div>
            @endforeach
        </div>
    </div>
</div>