<?php
// app/Services/CourseValidationService.php

namespace App\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
class CourseValidationService
{
    public function validateAndRetrieveCourse(string $slug): Course
    {
        $course = Course::where('slug', $slug)
            ->whereNull('deleted_at')
            ->where('status', 'active')
            ->first();

        if (!$course) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Course not found or inactive.');
        }

        return $course;
    }

    public function retrieveValidCourses(int $limit = 6): Collection
    {
        return Course::whereNull('deleted_at')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}