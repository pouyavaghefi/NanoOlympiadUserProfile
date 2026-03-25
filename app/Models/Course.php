<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\App;
class Course extends Model
{
    public function countEpisodes($courseId)
    {
        $episodeService = App::make(\App\Services\EpisodeValidationService::class);
        return $episodeService->retrieveEpisodesByCourse($courseId)->count();
    }

    public function isFree()
    {
        return $this->price == 0;
    }

    public function calculateRegisteredFreeUsers($courseId)
    {
        $userCount = DB::table('course_visitors')
            ->where('course_id', $courseId)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        $ipCount = DB::table('course_visitors')
            ->where('course_id', $courseId)
            ->whereNull('user_id')
            ->distinct('ip_address')
            ->count('ip_address');

        return $userCount + $ipCount;
    }

    public function calculateTotalEpisodeTimes($courseId)
    {
        $episodeService = App::make(\App\Services\EpisodeValidationService::class);
        $episodes = $episodeService->retrieveEpisodesByCourse($courseId);

        $totalSeconds = 0;

        foreach ($episodes as $episode) {
            list($hours, $minutes, $seconds) = explode(':', $episode->time);

            $totalSeconds += ($hours * 3600) + ($minutes * 60) + $seconds;
        }

        $totalHours = round($totalSeconds / 3600, 2);

        return $totalHours;
    }

}
