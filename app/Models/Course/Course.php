<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $guarded = [];

    public function episodes()
    {
        return $this->hasMany(Episode::class, 'course_id');
    }
}
