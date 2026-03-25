<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $table = 'episodes';
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function showStatus()
    {
        if ($this->show_status == 1) {
            return '<span class="badge bg-success" style="color:white">Active</span>';
        } else {
            return '<span class="badge bg-danger" style="color:white">Inactive</span>';
        }
    }
}
