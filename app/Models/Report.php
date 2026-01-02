<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['report_type', 'course_id', 'created_by', 'file_path', 'status'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }
}

