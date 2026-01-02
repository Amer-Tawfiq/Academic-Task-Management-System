<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamModel extends Model
{
    protected $fillable = ['course_id', 'uploaded_by', 'type', 'file_path', 'status'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

