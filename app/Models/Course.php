<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_name', 'course_code', 'department_id', 'teacher_id', 'completion_ratio'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function examModels()
    {
        return $this->hasMany(ExamModel::class);
    }

    public function progress()
    {
        return $this->hasOne(CourseProgress::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}

