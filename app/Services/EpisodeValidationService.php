<?php
// app/Services/EpisodeValidationService.php

namespace App\Services;

use App\Models\Episode;
use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EpisodeValidationService
{
    /**
     * Validate and retrieve the episode based on various conditions.
     *
     * @param string $slug
     * @param int $courseId
     * @return \App\Models\Episode
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function validateAndRetrieveEpisode(string $slug, int $courseId)
    {
        // Retrieve episode with conditions
        $episode = Episode::where('slug', $slug)
            ->where('course_id', $courseId)
            ->whereNull('deleted_at')
            ->where('show_status',1)
            ->first();

        if (!$episode) {
            throw new ModelNotFoundException('Episode not found or inactive.');
        }

        return $episode;
    }

    /**
     * Retrieve all episodes for a given course with necessary conditions.
     *
     * @param int $courseId
     * @return \Illuminate\Support\Collection
     */
    public function retrieveEpisodesByCourse(int $courseId)
    {
        return Episode::where('course_id', $courseId)
            ->where('show_status', 1)
            ->whereNull('deleted_at')
            ->get();
    }
}
