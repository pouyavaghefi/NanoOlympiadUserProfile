@php
    $totalHours = 0;
    $totalMinutes = 0;
    $totalSeconds = 0;

    $episodes = DB::table('episodes')->where('course_id', $course->id)->get();

    foreach ($episodes as $episode) {
       list($hours, $minutes, $seconds) = explode(":", $episode->time);

       $totalHours += (int) $hours;
       $totalMinutes += (int) $minutes;
       $totalSeconds += (int) $seconds;
    }

    $totalMinutes += floor($totalSeconds / 60);
    $totalSeconds = $totalSeconds % 60;

    $totalHours += floor($totalMinutes / 60);
    $totalMinutes = $totalMinutes % 60;

    $course_teacher_course = DB::table('course_teachers_course')->where('course_id', $course->id)->first();
    $course_category_course = DB::table('course_category_course')->where('course_id', $course->id)->get();
    if($course_teacher_course){
        $course_teacher = DB::table('course_teachers')->where('id', $course_teacher_course->teacher_id)->first();
        $course_teacher = DB::table('course_teachers')->where('id', $course_teacher_course->teacher_id)->first();
        $user = DB::table('users')->where('id', $course_teacher->user_id)->first();
        $fullName = $user->first_name . ' ' . $user->last_name;
    }else{
        $fullName = 'No teacher has been added!';
    }
    $registered_num = DB::table('course_registrations')->where('course_id', $course->id)->count();
    if($course->intro_video){
        $introVid = env('URL_ADMIN') . "/" . $course->intro_video;
    }else{
        $introVid = null;
    }
@endphp

<a href="{{ route('courses.show', $course->slug) }}?auth_token={{ request()->get('auth_token') }}">
    <div class="card">
        <div class="lesson_name">
            <div class="bg-primary d-flex justify-content-center align-items-center flex-column position-relative img-overlay">
                <img src="{{ env('URL_ADMIN') . '/' . $course->image_url }}" alt="course-img" class="img-fluid">
                <div class="position-absolute top-50 start-50 translate-middle">
                    <div class="avatar lg rounded-circle img-thumbnail d-flex justify-content-center align-items-center m-auto">
                        <i class="icofont-read-book fs-3"></i>
                    </div>
                    <h6 class="mb-0 fw-bold text-white fs-6 text-center mt-3">{{ $course->title }}</h6>
                </div>
                <a href="" class="btn bg-secondary btn-sm text-white top-0 position-absolute end-0 rounded-0" title="buy-button">
                    <i class="icofont-cart-alt me-2"></i> Enrolled
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="icofont-files-stack"></i>
                        <span class="ms-2">{{ $course->lectures ?? 0 }} Lessons</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="icofont-clock-time"></i>
                        <span class="ms-2">{{ sprintf('%02d:%02d:%02d', $totalHours, $totalMinutes, $totalSeconds) }} Hours</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="icofont-price"></i>
                        <span class="ms-2">${{ $course->price }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="icofont-ui-rating"></i>
                        <span class="ms-2">{{ $course->average_rating ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>
