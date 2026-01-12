<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\User;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('course','creator');

        // المدرس يرى تقاريره فقط
        if (auth()->user()->role_id == 1) {
            $query->where('created_by', auth()->id());
        }

        // الفلاتر
        if ($request->type)
            $query->where('report_type', $request->type);

        if ($request->course_id)
            $query->where('course_id', $request->course_id);

        if ($request->status)
            $query->where('status', $request->status);

        if ($request->search)
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('course_name','like','%'.$request->search.'%');
            });

        $reports = $query->latest()->paginate(10);
        $courses = Course::all();

        return view('reports.index', compact('reports','courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'report_type'=>'required',
            'course_id'=>'required',
            'file'=>'required|file'
        ]);

        $path = $request->file('file')->store('reports','public');

        Report::create([
            'report_type'=>$request->report_type,
            'course_id'=>$request->course_id,
            'created_by'=>auth()->id(),
            'file_path'=>$path
        ]);

        return back();
    }

    public function approve(Report $report)
    {
        $report->update(['status'=>'approved']);
        return back();
    }

    public function reject(Request $request, Report $report)
    {
        $report->update(['status'=>'rejected']);
        return back();
    }
}

