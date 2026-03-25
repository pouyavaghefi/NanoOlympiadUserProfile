<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Course\Course;
use App\Services\CourseValidationService;
use App\Services\EpisodeValidationService;
use Illuminate\Support\Facades\Cache;

class CourseController extends CourseMainController
{
    protected $courseValidationService;
    protected $episodeValidationService;

    // Inject the services via constructor
    public function __construct(CourseValidationService $courseValidationService, EpisodeValidationService $episodeValidationService)
    {
        $this->courseValidationService = $courseValidationService;
        $this->episodeValidationService = $episodeValidationService;
    }

    public function show($slug)
    {
        try {
            // Cache the course using slug
            $course = Cache::remember("course_{$slug}", now()->addMinutes(30), function () use ($slug) {
                return $this->courseValidationService->validateAndRetrieveCourse($slug);
            });

            // Cache the episodes of the course
            $episodes = Cache::remember("course_{$course->id}_episodes", now()->addMinutes(30), function () use ($course) {
                return $this->episodeValidationService->retrieveEpisodesByCourse($course->id);
            });

            // Pick the first episode by episode_number
            $play = $episodes->sortBy('episode_number')->first();

            // Trigger visit event (can also be queued)
//            event(new CourseVisited(
//                $course->id,
//                auth()->check() ? auth()->id() : null,
//                request()->ip()
//            ));

            // Update view count only once per session
            if (!session()->has('viewed_course_' . $course->id)) {
                $course->increment('view_count');
                session()->put('viewed_course_' . $course->id, true);
            }

            if (!session()->has('viewed_episode_' . $play->id)) {
                $play->increment('view_count');
                session()->put('viewed_episode_' . $play->id, true);
            }

            return view('courses.player.index', [
                'course' => $course,
                'episodes' => $episodes,
                'play' => $play,
            ]);
        } catch (\Exception $e) {
            return abort(404, $e->getMessage());
        }
    }

    public function showCourseNextEpisode($slug, $epi)
    {
        try {
            $course = $this->courseValidationService->validateAndRetrieveCourse($slug);

            $play = $this->episodeValidationService->validateAndRetrieveEpisode($epi, $course->id);

            $episodes = $this->episodeValidationService->retrieveEpisodesByCourse($course->id);

            if (!session()->has('viewed_episode_' . $play->id)) {
                $episode_viewers = $play->view_count;
                $episode_new_viewers = $episode_viewers + 1;
                $play->view_count = $episode_new_viewers;
                $play->save();

                session()->put('viewed_episode_' . $play->id, true);
            }

            return view('courses.player.index', compact('course', 'episodes', 'play'));
        } catch (\Exception $e) {
            return abort(404, $e->getMessage());
        }
    }

    public function index()
    {
        $courses = Course::where('is_active',1)->get();

        return view('courses.index', compact('courses'));
    }
    public function enrolled()
    {
        $registeredCourses = DB::table('course_registrations')->where('user_id',auth()->user()->id)->get();
        return view('courses.enrolled', compact('registeredCourses'));
    }

//    public function show($slug)
//    {
//        $course = DB::table('courses')->whereSlug($slug)->first();
//
//        $registeredList = DB::table('course_registrations')->whereCourseId($course->id)->get();
//
//        $episodes = DB::table('episodes')
//            ->where('course_id', $course->id)
//            ->whereNull('deleted_at')
//            ->get();
//
//        $episode = DB::table('episodes')
//            ->where('course_id', $course->id)
//            ->orderBy('episode_number', 'asc')
//            ->whereNull('deleted_at')
//            ->first();
//
//        return view('courses.show', compact('course','episodes','episode','registeredList'));
//    }
}
