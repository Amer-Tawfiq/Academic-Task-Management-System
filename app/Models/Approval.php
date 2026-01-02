<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = ['report_id', 'approved_by', 'comment'];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

