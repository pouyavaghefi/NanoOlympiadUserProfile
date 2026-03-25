<div class="row clearfix">
    <div class="col-md-12">
        <div class="team_members video-list">
            <!-- Add Related Courses Title -->
            <h4 class="text-center mb-4"><a href="{{ $course->course_playlist }}" target="_blank" title="View YT Playlist" style="color:#469AA9">{{ $course->title }}</a></h4>

            <div class="owl-carousel owl-theme owl-carouselthree">
                <div class="item text-center">
                    <!-- Wrap the whole thumbnail container in an anchor tag -->
                    <a href="" class="d-block">
                        <div class="thumbnail-wrapper">
                            <img src="{{ env('URL_ADMIN') }}/{{ $course->image_url ?? '/assets/img/course/default.jpg' }}" alt="{{ $course->title }}" class="rounded-3 mb-3 img-thumbnail shadow-sm playlist-thumbnail">
                            <!-- Overlay title, lessons, and duration -->
                            <div class="overlay-text">
                                {{-- <h5 class="text-white">{{ $course->title }}</h5> --}}
                                <p class="text-white">Lessons: {{ $course->countEpisodes($course->id) }}</p>
                                <p class="text-white">Duration: {{ $course->calculateTotalEpisodeTimes($course->id) ?? 'N/A' }} Hours</p>
{{--                                <a href="https://www.youtube.com/watch?v=Jv1_vFU8EZo&list=PLb8XLLNaSpf5uE36NoARmkVgcRGWSu3Wf&index=2" class="btn btn-sm btn-primary">View YT Playlist</a>--}}
                            </div>

                        </div>
                        <div class="video-live">
                            <span class="person-status px-1 py-1"><i class="icofont-play-alt-2 color-light-info"></i></span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .thumbnail-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .playlist-thumbnail {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
    }

    .playlist-thumbnail:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .overlay-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: white;
        z-index: 10;
        background-color: rgba(0, 0, 0, 0.5);
        padding: 10px;
        border-radius: 5px;
    }

    .overlay-text h5 {
        font-size: 0.6rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .overlay-text p {
        font-size: 0.9rem;
        color: #f2f2f2;
    }

    .video-live {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: rgba(0, 0, 0, 0.6);
        padding: 5px;
        border-radius: 20px;
    }

    .person-status i {
        font-size: 14px;
    }

    .video-setting-icon {
        margin-top: 10px;
    }

    .btn-playlist {
        display: inline-block;
        background-color: #007bff;
        color: #fff;
        padding: 12px 20px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 30px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 100%;
        margin-top: 15px;
    }

    .btn-playlist i {
        margin-right: 8px;
    }

    .btn-playlist:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .btn-playlist:active {
        transform: translateY(1px);
    }

    .btn-playlist:focus {
        outline: none;
    }

    .course-price-wrapper {
        margin-top: 15px;
        text-align: center;
    }

    .course-price {
        font-size: 1.2rem;
        font-weight: bold;
        color: white;
        background-color: rgba(0, 0, 0, 0.6);
        padding: 5px 15px;
        border-radius: 20px;
    }
</style>
