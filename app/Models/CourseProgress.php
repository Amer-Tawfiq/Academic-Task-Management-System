<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseProgress extends Model
{
    protected $fillable = ['course_id', 'percentage', 'updated_by'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

